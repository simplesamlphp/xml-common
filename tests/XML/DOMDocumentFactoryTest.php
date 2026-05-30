<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use DOMException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\DOMDocumentFactory;

use function strval;

/**
 * @package simplesamlphp\xml-common
 */
#[CoversClass(DOMDocumentFactory::class)]
#[Group('domdocument')]
final class DOMDocumentFactoryTest extends TestCase
{
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
        $this->assertInstanceOf(\Dom\Element::class, $normalizedRoot);

        $normalizedDocRoot = $normalizedDoc->documentElement;
        $this->assertInstanceOf(\Dom\Element::class, $normalizedDocRoot);

        $this->assertSame(
            $normalizedRoot->C14N(),
            $normalizedDocRoot->C14N(),
        );
    }
}
