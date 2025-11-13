<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XPath;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XPath\XPath;

/**
 * Tests for the SimpleSAML\XPath\XPath helper.
 */
#[CoversClass(XPath::class)]
final class XPathTest extends TestCase
{
    public function testGetXPathCachesPerDocumentAndRegistersCoreNamespaces(): void
    {
        // Doc A with an xml:space attribute to validate 'xml' prefix usage works
        $docA = new \DOMDocument();
        $docA->loadXML(<<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<root xml:space="preserve" xmlns:xml="http://www.w3.org/XML/1998/namespace">
  <child>value</child>
</root>
XML);

        // Doc B is different
        $docB = new \DOMDocument();
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
        $this->assertInstanceOf(\DOMElement::class, $rootA);
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
        $doc = new \DOMDocument();
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
        $doc = new \DOMDocument();
        $doc->loadXML('<root><x/></root>');
        $xp = XPath::getXPath($doc);

        // If xpQuery throws a specific exception, put that class here instead of \Throwable.
        $this->expectException(\Throwable::class);
        // Keep message assertion resilient to libxml version differences.
        $this->expectExceptionMessageMatches('/(XPath|expression).*invalid|malformed|error/i');

        // Malformed XPath: missing closing bracket
        $root = $doc->documentElement;
        $this->assertInstanceOf(\DOMElement::class, $root);

        // Avoid emitting a PHP warning; let xpQuery surface it as an exception.
        \libxml_use_internal_errors(true);
        try {
            XPath::xpQuery($root, '//*[', $xp);
        } finally {
            $errors = \libxml_get_errors();
            self::assertCount(1, $errors);
            self::assertEquals("Invalid expression\n", $errors[0]->message);
            \libxml_clear_errors();
            \libxml_use_internal_errors(false);
        }
    }
}
