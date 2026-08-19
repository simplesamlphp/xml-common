<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use Dom;
use DOMException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RequiresOperatingSystemFamily;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\DOMDocumentFactory;

use function bin2hex;
use function file_put_contents;
use function getmypid;
use function is_file;
use function mb_convert_encoding;
use function random_bytes;
use function strval;
use function sys_get_temp_dir;

/**
 * @package simplesamlphp\xml-common
 */
#[CoversClass(DOMDocumentFactory::class)]
#[Group('domdocument')]
final class DOMDocumentFactoryTest extends TestCase
{
    private string $secretFile;

    private string $marker;


    protected function setUp(): void
    {
        $this->marker = 'XXE_TEST_MARKER_' . bin2hex(random_bytes(16));
        $this->secretFile = sys_get_temp_dir() . '/xml-common-xxe-test-' . getmypid() . '.txt';
        file_put_contents($this->secretFile, $this->marker);
    }


    protected function tearDown(): void
    {
        if (is_file($this->secretFile)) {
            unlink($this->secretFile);
        }
    }


    /**
     * Classic external-entity payload aimed at a file we control.
     * The factory must reject the document; the marker must never appear.
     */
    #[RequiresOperatingSystemFamily('Linux')]
    public function testExternalEntityCannotReadLocalFileUsingDefaultOptions(): void
    {
        // file:// URI pointing at our temporary secret
        $uri = 'file:///' . $this->secretFile;
        $payload = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE foo [
  <!ENTITY xxe SYSTEM "{$uri}">
]>
<foo>&xxe;</foo>
XML;

        $doc = Dom\XMLDocument::createFromString($payload, DOMDocumentFactory::getDefaultOptions());
        // If we reach here the protection failed – check that the secret did not leak into the document.
        $xml = $doc->saveXml();
        $this->assertStringNotContainsString(
            $this->marker,
            (string)$xml,
            'XXE succeeded: local file content was expanded into the document',
        );
    }


    /**
     * Classic external-entity payload aimed at a file we control.
     * The factory must reject the document; the marker must never appear.
     */
    #[RequiresOperatingSystemFamily('Linux')]
    public function testExternalEntityCannotReadLocalFileUsingNonDefaultOptions(): void
    {
        // file:// URI pointing at our temporary secret
        $uri = 'file://' . $this->secretFile;
        $payload = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE foo [
  <!ENTITY xxe SYSTEM "{$uri}">
]>
<foo>&xxe;</foo>
XML;

        $options = DOMDocumentFactory::getDefaultOptions();
        if (defined('LIBXML_NO_XXE')) {
            $options &= ~\LIBXML_NO_XXE;
        }

        $doc = Dom\XMLDocument::createFromString($payload, $options | \LIBXML_NOENT);
        // Check that the secret did leak into the document.
        $xml = $doc->saveXml();
        $this->assertStringContainsString(
            $this->marker,
            (string)$xml,
            'XXE succeeded: local file content was expanded into the document',
        );
    }


    /**
     * Same idea with a UTF-16LE encoded payload.
     */
    #[RequiresOperatingSystemFamily('Linux')]
    public function testUtf16ExternalEntityCannotReadLocalFileUsingDefaultOptions(): void
    {
        $uri = 'file://' . $this->secretFile;

        $utf8 = <<<XML
<?xml version="1.0" encoding="UTF-16"?>
<!DOCTYPE foo [
  <!ENTITY xxe SYSTEM "{$uri}">
]>
<foo>&xxe;</foo>
XML;

        $payload = "\xFF\xFE" . mb_convert_encoding($utf8, 'UTF-16LE', 'UTF-8');

        $doc = Dom\XMLDocument::createFromString($payload, DOMDocumentFactory::getDefaultOptions());
        // If we reach here the protection failed – check that the secret did not leak into the document.
        $xml = $doc->saveXml();
        $this->assertStringNotContainsString(
            $this->marker,
            (string)$xml,
            'XXE succeeded: local file content was expanded into the document',
        );
    }


    /**
     * Same idea with a UTF-16LE encoded payload.
     */
    #[RequiresOperatingSystemFamily('Linux')]
    public function testUtf16ExternalEntityCannotReadLocalFileUsingNonDefaultOptions(): void
    {
        $uri = 'file://' . $this->secretFile;

        $utf8 = <<<XML
<?xml version="1.0" encoding="UTF-16"?>
<!DOCTYPE foo [
  <!ENTITY xxe SYSTEM "{$uri}">
]>
<foo>&xxe;</foo>
XML;

        $payload = "\xFF\xFE" . mb_convert_encoding($utf8, 'UTF-16LE', 'UTF-8');

        $options = DOMDocumentFactory::getDefaultOptions();
        if (defined('LIBXML_NO_XXE')) {
            $options &= ~\LIBXML_NO_XXE;
        }

        $doc = Dom\XMLDocument::createFromString($payload, $options | \LIBXML_NOENT);
        // Check that the secret did leak into the document.
        $xml = mb_convert_encoding((string)$doc->saveXml(), 'UTF-8', 'UTF-16LE');
        $this->assertStringContainsString(
            $this->marker,
            (string)$xml,
            'XXE succeeded: local file content was expanded into the document',
        );
    }


    public function testNotXmlStringRaisesAnException(): void
    {
        $this->expectException(DOMException::class);
        DOMDocumentFactory::fromString('this is not xml');
    }


    public function testXmlStringIsCorrectlyLoaded(): void
    {
        $xml = '<root/>';

        $document = DOMDocumentFactory::fromString($xml);

        $this->assertXmlStringEqualsXmlString($xml, strval($document->saveXml()));
    }


    public function testFileThatDoesNotExistIsNotAccepted(): void
    {
        $this->expectException(RuntimeException::class);
        $filename = 'DoesNotExist.ext';
        DOMDocumentFactory::fromFile($filename);
    }


    public function testFileThatDoesNotContainXMLCannotBeLoaded(): void
    {
        $this->expectException(DOMException::class);
        DOMDocumentFactory::fromFile('tests/resources/xml/domdocument_invalid_xml.xml');
    }


    public function testFileWithValidXMLCanBeLoaded(): void
    {
        $file = 'tests/resources/xml/domdocument_valid_xml.xml';
        $document = DOMDocumentFactory::fromFile($file);

        $this->assertXmlStringEqualsXmlFile($file, strval($document->saveXml()));
    }


    public function testFileThatContainsDocTypeIsNotAccepted(): void
    {
        $file = 'tests/resources/xml/domdocument_doctype.xml';
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Dangerous XML detected, DOCTYPE nodes are not allowed in the XML body',
        );
        DOMDocumentFactory::fromFile($file);
    }


    public function testStringThatContainsDocTypeIsNotAccepted(): void
    {
        $xml = '<!DOCTYPE foo [<!ELEMENT foo ANY > <!ENTITY xxe SYSTEM "file:///dev/random" >]><foo />';
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Dangerous XML detected, DOCTYPE nodes are not allowed in the XML body',
        );
        DOMDocumentFactory::fromString($xml);
    }


    public function testStringThatContainsDocTypeIsNotAccepted2(): void
    {
        $xml = '<?xml version="1.0" encoding="ISO-8859-1"?>
               <!DOCTYPE foo [<!ENTITY % exfiltrate SYSTEM "file://dev/random">%exfiltrate;]>
               <foo>y</foo>';
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Dangerous XML detected, DOCTYPE nodes are not allowed in the XML body',
        );
        DOMDocumentFactory::fromString($xml);
    }


    public function testEmptyFileIsNotValid(): void
    {
        $file = 'tests/resources/xml/domdocument_empty.xml';
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('does not have content');
        DOMDocumentFactory::fromFile($file);
    }


    public function testEmptyStringIsNotValid(): void
    {
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage(
            'Expected a non-whitespace string. Got: ""',
        );

        /** @phpstan-ignore-next-line argument.type */
        DOMDocumentFactory::fromString('');
    }


    public function testNormalizeDocument(): void
    {
        $normalized = DOMDocumentFactory::fromFile('tests/resources/xml/domdocument_normalized.xml');
        $notNormalized = DOMDocumentFactory::fromFile('tests/resources/xml/domdocument_not_normalized.xml');
        $normalizedDoc = DOMDocumentFactory::normalizeDocument($notNormalized);

        $normalizedRoot = $normalized->documentElement;
        $this->assertInstanceOf(Dom\Element::class, $normalizedRoot);

        $normalizedDocRoot = $normalizedDoc->documentElement;
        $this->assertInstanceOf(Dom\Element::class, $normalizedDocRoot);

        $this->assertSame(
            $normalizedRoot->C14N(),
            $normalizedDocRoot->C14N(),
        );
    }
}
