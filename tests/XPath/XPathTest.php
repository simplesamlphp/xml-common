<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XPath;

use DOMDocument;
use DOMElement;
use DOMText;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XPath\XPath;
use Throwable;

use function libxml_clear_errors;
use function libxml_use_internal_errors;

/**
 * Tests for the SimpleSAML\XPath\XPath helper.
 */
#[CoversClass(XPath::class)]
final class XPathTest extends TestCase
{
    public function testGetXPathCachesPerDocumentAndRegistersCoreNamespaces(): void
    {
        // Doc A with an xml:space attribute to validate 'xml' prefix usage works
        $docA = new DOMDocument();
        $docA->loadXML(<<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<root xml:space="preserve" xmlns:xml="http://www.w3.org/XML/1998/namespace">
  <child>value</child>
</root>
XML);

        // Doc B is different
        $docB = new DOMDocument();
        $docB->loadXML(<<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<another><node/></another>
XML);

        $xpA1 = XPath::getXPath($docA);
        $xpA2 = XPath::getXPath($docA);
        $xpB = XPath::getXPath($docB);

        // Cached instance reused per same document
        $this->assertSame($xpA1, $xpA2);
        // Different document => different DOMXPath instance
        $this->assertNotSame($xpA1, $xpB);

        // 'xml' prefix registered: query should be valid and return xml:space attribute
        $rootA = $docA->documentElement;
        $this->assertInstanceOf(DOMElement::class, $rootA);
        $attrs = XPath::xpQuery($rootA, '@xml:space', $xpA1);
        $this->assertCount(1, $attrs);
        $this->assertSame('preserve', $attrs[0]->nodeValue);
    }


    public function testAncestorNamespaceRegistrationAllowsCustomPrefixes(): void
    {
        // Custom namespace declared on the root; query from a descendant node
        $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<r xmlns:foo="https://example.org/foo">
  <a>
    <b>
      <foo:item>ok</foo:item>
    </b>
  </a>
</r>
XML;
        $doc = new DOMDocument();
        $doc->loadXML($xml);

        // Use a deep context node to ensure ancestor-walk picks up xmlns:foo from root
        $context = $doc->getElementsByTagName('b')->item(0);
        $this->assertInstanceOf(\DOMElement::class, $context);

        $xp = XPath::getXPath($context);

        $nodes = XPath::xpQuery($context, 'foo:item', $xp);
        $this->assertCount(1, $nodes);
        $this->assertSame('ok', $nodes[0]->textContent);
    }


    public function testXpQueryThrowsOnMalformedExpression(): void
    {
        $doc = new DOMDocument();
        $doc->loadXML('<root><x/></root>');
        $xp = XPath::getXPath($doc);

        // If xpQuery throws a specific exception, put that class here instead of Throwable.
        $this->expectException(Throwable::class);
        // Keep message assertion resilient to libxml version differences.
        $this->expectExceptionMessageMatches('/(XPath|expression).*invalid|malformed|error/i');

        // Malformed XPath: missing closing bracket
        $root = $doc->documentElement;
        $this->assertInstanceOf(DOMElement::class, $root);

        // Avoid emitting a PHP warning; let xpQuery surface it as an exception.
        libxml_use_internal_errors(true);
        try {
            XPath::xpQuery($root, '//*[', $xp);
        } finally {
            $errors = libxml_get_errors();
            self::assertCount(1, $errors);
            self::assertEquals("Invalid expression\n", $errors[0]->message);
            libxml_clear_errors();
            libxml_use_internal_errors(false);
        }
    }


    public function testXmlnsDetectionRegistersPrefixedNamespace(): void
    {
        // This XML ensures we hit the detection branch and pass the second guard:
        // - xmlns:foo="urn:two" should be detected and registered
        $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <ctx xmlns:foo="urn:two">
    <foo:item>ok</foo:item>
  </ctx>
</root>
XML;

        $doc = new DOMDocument();
        $doc->loadXML($xml);

        $context = $doc->getElementsByTagName('ctx')->item(0);
        $this->assertInstanceOf(DOMElement::class, $context);

        $xp = XPath::getXPath($context);

        // Passing the guards should register 'foo' so this resolves
        $nodes = XPath::xpQuery($context, 'foo:item', $xp);
        $this->assertCount(1, $nodes);
        $this->assertSame('ok', $nodes[0]->textContent);
    }


    public function testNormalizationFromTextNode(): void
    {
        $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<r xmlns:foo="https://example.org/foo">
  <a><foo:item>ok</foo:item></a>
</r>
XML;
        $doc = new DOMDocument();
        $doc->loadXML($xml);

        $item = $doc->getElementsByTagNameNS('https://example.org/foo', 'item')->item(0);
        $this->assertInstanceOf(DOMElement::class, $item);

        $text = $item->firstChild; // DOMText node inside <foo:item>
        $this->assertInstanceOf(DOMText::class, $text);

        // getXPath should handle a non-element node, normalize to the nearest element ancestor,
        // and register the 'foo' namespace so a prefixed query works.
        $xp = XPath::getXPath($text);

        $nodes = XPath::xpQuery($text, 'ancestor::foo:item', $xp);
        $this->assertCount(1, $nodes);
        $this->assertSame('ok', $nodes[0]->textContent);
    }


    public function testNormalizationFromAttributeNode(): void
    {
        $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<root xmlns:bar="urn:bar">
  <bar:elt bar:attr="v"><x/></bar:elt>
</root>
XML;
        $doc = new DOMDocument();
        $doc->loadXML($xml);

        $elt = $doc->getElementsByTagNameNS('urn:bar', 'elt')->item(0);
        $this->assertInstanceOf(\DOMElement::class, $elt);

        $attr = $elt->getAttributeNodeNS('urn:bar', 'attr');
        /** @var \DOMAttr $attr */

        // getXPath should normalize from DOMAttr to the element and ensure 'bar' is registered.
        $xp = XPath::getXPath($attr);

        $nodes = XPath::xpQuery($attr, 'ancestor::bar:elt', $xp);
        $this->assertCount(1, $nodes);

        // Ensure we have an element before calling element-only methods.
        $this->assertInstanceOf(\DOMElement::class, $nodes[0]);
        /** @var \DOMElement $el */
        $el = $nodes[0];
        $this->assertSame('v', $el->getAttributeNS('urn:bar', 'attr'));
    }


    public function testSkipsDefaultNamespaceDeclarationDoesNotCreateUsableXmlnsPrefix(): void
    {
        // Default namespace present; no prefixed declaration.
        // The guard should skip registering 'xmlns' as a usable prefix.
        $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<root xmlns="urn:def">
  <a><b>t</b></a>
</root>
XML;
        $doc = new DOMDocument();
        $doc->loadXML($xml);

        $context = $doc->documentElement?->getElementsByTagName('b')->item(0);
        $this->assertInstanceOf(\DOMElement::class, $context);

        $xp = XPath::getXPath($context);

        // Using 'xmlns' as a prefix should fail because the code skips binding it.
        libxml_use_internal_errors(true);
        try {
            $this->expectException(\Throwable::class);
            // The XPath helper wraps libxml errors into a generic message:
            $this->expectExceptionMessage('Malformed XPath query or invalid contextNode provided.');
            XPath::xpQuery($context, 'xmlns:b', $xp);
        } finally {
            $errors = libxml_get_errors();
            $this->assertEquals("Undefined namespace prefix\n", $errors[0]->message);
            libxml_clear_errors();
            libxml_use_internal_errors(false);
        }
    }


    public function testSkipsEmptyUriNamespaceDeclaration(): void
    {
        // An empty-URI namespace declaration must be ignored by the guard ($uri === '').
        $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<root xmlns:empty="">
  <child>t</child>
</root>
XML;
        // Attempting to use the 'empty' prefix should fail because it wasn't registered.
        libxml_use_internal_errors(true);
        try {
            $doc = new DOMDocument();
            $doc->loadXML($xml);
            $context = $doc->getElementsByTagName('child')->item(0);
            $this->assertInstanceOf(\DOMElement::class, $context);

            $xp = XPath::getXPath($context);
            $this->expectException(\Throwable::class);
            // The XPath helper wraps libxml errors into a generic message:
            $this->expectExceptionMessage('Malformed XPath query or invalid contextNode provided.');
            XPath::xpQuery($context, 'empty:whatever', $xp);
        } finally {
            $errors = libxml_get_errors();
            $this->assertEquals("xmlns:empty: Empty XML namespace is not allowed\n", $errors[0]->message);
            $this->assertEquals("Undefined namespace prefix\n", $errors[1]->message);
            libxml_clear_errors();
            libxml_use_internal_errors(false);
        }
    }


    public function testXmlnsPrefixedDeclarationRegistersNamespaceViaAttributeBranch(): void
    {
        // Build DOM programmatically to ensure xmlns:foo exists as attribute.
        $doc = new DOMDocument('1.0', 'UTF-8');
        $root = $doc->createElement('root');
        $doc->appendChild($root);

        // Add xmlns:foo on the root (attribute-branch should detect and register)
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:foo', 'https://example.org/foo');

        // Deep subtree that uses foo prefix but the context node itself is unprefixed
        $ctx = $doc->createElement('ctx');
        $root->appendChild($ctx);

        $fooItem = $doc->createElementNS('https://example.org/foo', 'foo:item', 'ok');
        $ctx->appendChild($fooItem);

        // Use the unprefixed context to ensure ancestor-walk is required.
        $xp = XPath::getXPath($ctx);
        $nodes = XPath::xpQuery($ctx, 'foo:item', $xp);

        // If attribute-branch registered 'foo', the query resolves.
        $this->assertCount(1, $nodes);
        $this->assertSame('ok', $nodes[0]->textContent);
    }


    /**
     * Provides relative file paths for the two XML variants.
     *
     * @return array<string, array{0: string}>
     */
    public static function xmlVariantsProviderForTopLevelSlatePerson(): array
    {
        $base = dirname(__FILE__, 3) . '/tests/resources/xml';

        return [
            "Ancestor-declared 'slate'; top-level person AFTER attributes" => [
                $base . '/success_response_a.xml',
                false,
                false,
            ],
            "Ancestor-declared 'slate'; top-level person BEFORE attributes" => [
                $base . '/success_response_b.xml',
                false,
                false,
            ],
            "Descendant-only 'slate'; no ancestor binding (fails without autoregister)" => [
                $base . '/success_response_c.xml',
                false,
                true,
            ],
            "Descendant-only 'slate'; no ancestor binding (succeeds with autoregister)" => [
                $base . '/success_response_c.xml',
                true,
                false,
            ],
        ];
    }

    /**
     * Ensure that absolute XPath '/foo:serviceResponse/foo:authenticationSuccess/slate:person'
     * finds the same top-level slate:person regardless of whether it appears before or after
     * cas:attributes in the document, even when the slate prefix is only declared on the element itself.
     */
    #[DataProvider('xmlVariantsProviderForTopLevelSlatePerson')]
    public function testAbsoluteXPathFindsTopLevelSlatePerson(
        string $filePath,
        bool $autoregister,
        bool $shouldFail,
    ): void {
        $doc = DOMDocumentFactory::fromFile($filePath);

        $fooNs = 'https://example.org/foo';
        /** @var \DOMElement|null $attributesNode */
        $attributesNode = $doc->getElementsByTagNameNS($fooNs, 'attributes')->item(0);
        $this->assertNotNull($attributesNode, 'Attributes element not found');

        $xp = XPath::getXPath($attributesNode, $autoregister);
        $query = '/foo:serviceResponse/foo:authenticationSuccess/slate:person';

        if ($shouldFail) {
            libxml_use_internal_errors(true);
            try {
                $this->expectException(\SimpleSAML\Assert\AssertionFailedException::class);
                $this->expectExceptionMessage('Malformed XPath query or invalid contextNode provided.');
                XPath::xpQuery($attributesNode, $query, $xp);
            } finally {
                $errors = libxml_get_errors();
                $this->assertNotEmpty($errors);
                $this->assertSame("Undefined namespace prefix\n", $errors[0]->message);
                libxml_clear_errors();
                libxml_use_internal_errors(false);
            }
            return;
        }

        $nodes = XPath::xpQuery($attributesNode, $query, $xp);
        $this->assertCount(1, $nodes);
        $this->assertSame('12345_top', trim($nodes[0]->textContent));
    }


    public function testFindElementFindsDirectChildUnprefixed(): void
    {
        $doc = new DOMDocument();
        $doc->loadXML('<root><target>t</target><other/></root>');

        $root = $doc->documentElement;
        $this->assertInstanceOf(DOMElement::class, $root);

        $found = XPath::findElement($root, 'target');
        $this->assertInstanceOf(DOMElement::class, $found);
        $this->assertSame('target', $found->localName);
        $this->assertSame('t', $found->textContent);
    }


    public function testFindElementFindsDirectChildWithPrefixWhenNsOnRoot(): void
    {
        $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<root xmlns:foo="https://example.org/foo">
  <foo:item>ok</foo:item>
</root>
XML;
        $doc = new DOMDocument();
        $doc->loadXML($xml);

        $root = $doc->documentElement;
        $this->assertInstanceOf(DOMElement::class, $root);

        // Namespace is declared on root, so getXPath($doc) used by findElement knows 'foo'
        $found = XPath::findElement($root, 'foo:item');
        $this->assertInstanceOf(DOMElement::class, $found);
        $this->assertSame('item', $found->localName);
        $this->assertSame('https://example.org/foo', $found->namespaceURI);
        $this->assertSame('ok', $found->textContent);
    }


    public function testFindElementReturnsFalseWhenNotFoundAndDoesNotDescend(): void
    {
        // 'target' is a grandchild; findElement should only match direct children via './name'
        $doc = new DOMDocument();
        $doc->loadXML('<root><container><target/></container></root>');

        $root = $doc->documentElement;
        $this->assertInstanceOf(DOMElement::class, $root);

        $found = XPath::findElement($root, 'target');
        $this->assertFalse($found, 'Should return false for non-direct child');
    }


    public function testFindElementThrowsIfNoOwnerDocument(): void
    {
        // A standalone DOMElement (not created by a DOMDocument) has no ownerDocument
        $ref = new \DOMElement('container');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot search, no DOMDocument available');
        XPath::findElement($ref, 'anything');
    }
}
