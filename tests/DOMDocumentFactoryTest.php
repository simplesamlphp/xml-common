<?php

declare(strict_types=1);

namespace SimpleSAML\SAML2;

use InvalidArgumentException;
use RuntimeException;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\Exception\UnparseableXmlException;

/**
 * @covers \SimpleSAML\XML\DOMDocumentFactory
 * @package simplesamlphp\xml-common
 */
final class DOMDocumentFactoryTest extends TestCase
{
    private const FRAMEWORK = 'vendor/simplesamlphp/xml-common/tests';

    /**
     * @group domdocument
     * @return void
     */
    public function testNotXmlStringRaisesAnException(): void
    {
        $this->expectException(UnparseableXmlException::class);
        DOMDocumentFactory::fromString('this is not xml');
    }


    /**
     * @group domdocument
     * @return void
     */
    public function testXmlStringIsCorrectlyLoaded(): void
    {
        $xml = '<root/>';

        $document = DOMDocumentFactory::fromString($xml);

        $this->assertXmlStringEqualsXmlString($xml, $document->saveXML());
    }


    /**
     * @return void
     */
    public function testFileThatDoesNotExistIsNotAccepted(): void
    {
        $this->expectException(RuntimeException::class);
        $filename = 'DoesNotExist.ext';
        DOMDocumentFactory::fromFile($filename);
    }


    /**
     * @group domdocument
     * @return void
     */
    public function testFileThatDoesNotContainXMLCannotBeLoaded(): void
    {
        $this->expectException(RuntimeException::class);
        DOMDocumentFactory::fromFile(self::FRAMEWORK . '/resources/xml/domdocument_invalid_xml.xml');
    }


    /**
     * @group domdocument
     * @return void
     */
    public function testFileWithValidXMLCanBeLoaded(): void
    {
        $file = self::FRAMEWORK . '/resources/xml/domdocument_valid_xml.xml';
        $document = DOMDocumentFactory::fromFile($file);

        $this->assertXmlStringEqualsXmlFile($file, $document->saveXML());
    }


    /**
     * @group                    domdocument
     * @return void
     */
    public function testFileThatContainsDocTypeIsNotAccepted(): void
    {
        $file = self::FRAMEWORK . '/resources/xml/domdocument_doctype.xml';
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Dangerous XML detected, DOCTYPE nodes are not allowed in the XML body'
        );
        DOMDocumentFactory::fromFile($file);
    }


    /**
     * @group                    domdocument
     * @return void
     */
    public function testStringThatContainsDocTypeIsNotAccepted(): void
    {
        $xml = '<!DOCTYPE foo [<!ELEMENT foo ANY > <!ENTITY xxe SYSTEM "file:///dev/random" >]><foo />';
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Dangerous XML detected, DOCTYPE nodes are not allowed in the XML body'
        );
        DOMDocumentFactory::fromString($xml);
    }


    /**
     * @group                    domdocument
     * @return void
     */
    public function testEmptyFileIsNotValid(): void
    {
        $file = self::FRAMEWORK . '/resources/xml/domdocument_empty.xml';
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('does not have content');
        DOMDocumentFactory::fromFile($file);
    }


    /**
     * @group                    domdocument
     * @return void
     */
    public function testEmptyStringIsNotValid(): void
    {
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage(
            'Expected a different value than "".'
        );
        DOMDocumentFactory::fromString("");
    }
}
