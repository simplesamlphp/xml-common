<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\DOM\DOMDocument;
use SimpleSAML\XML\Exception\UnparseableXMLException;

use function strval;

/**
 * @package simplesamlphp\xml-common
 */
#[CoversClass(DOMDocument::class)]
#[Group('domdocument')]
final class DOMDocumentTest extends TestCase
{
    public function testNotXmlStringRaisesAnException(): void
    {
        $this->expectException(UnparseableXMLException::class);
        DOMDocument::fromString('this is not xml');
    }


    public function testXmlStringIsCorrectlyLoaded(): void
    {
        $xml = '<root/>';

        $document = DOMDocument::fromString($xml);

        $this->assertXmlStringEqualsXmlString($xml, strval($document->saveXML()));
    }


    public function testFileThatDoesNotExistIsNotAccepted(): void
    {
        $this->expectException(RuntimeException::class);
        $filename = 'DoesNotExist.ext';
        DOMDocument::fromFile($filename);
    }


    public function testFileThatDoesNotContainXMLCannotBeLoaded(): void
    {
        $this->expectException(RuntimeException::class);
        DOMDocument::fromFile('tests/resources/xml/domdocument_invalid_xml.xml');
    }


    public function testFileWithValidXMLCanBeLoaded(): void
    {
        $file = 'tests/resources/xml/domdocument_valid_xml.xml';
        $document = DOMDocument::fromFile($file);

        $this->assertXmlStringEqualsXmlFile($file, strval($document->saveXML()));
    }


    public function testFileThatContainsDocTypeIsNotAccepted(): void
    {
        $file = 'tests/resources/xml/domdocument_doctype.xml';
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Dangerous XML detected, DOCTYPE nodes are not allowed in the XML body',
        );
        DOMDocument::fromFile($file);
    }


    public function testStringThatContainsDocTypeIsNotAccepted(): void
    {
        $xml = '<!DOCTYPE foo [<!ELEMENT foo ANY > <!ENTITY xxe SYSTEM "file:///dev/random" >]><foo />';
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Dangerous XML detected, DOCTYPE nodes are not allowed in the XML body',
        );
        DOMDocument::fromString($xml);
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
        DOMDocument::fromString($xml);
    }


    public function testEmptyFileIsNotValid(): void
    {
        $file = 'tests/resources/xml/domdocument_empty.xml';
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('does not have content');
        DOMDocument::fromFile($file);
    }


    public function testEmptyStringIsNotValid(): void
    {
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage(
            'Expected a non-whitespace string. Got: ""',
        );
        DOMDocument::fromString('');
    }
}
