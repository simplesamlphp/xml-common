<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\Helper\ExtendableElement;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SchemaValidationTestTrait;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XMLSchema\Exception\InvalidDOMElementException;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\XML\ExtendableElementTest
 *
 * @package simplesamlphp\xml-common
 */
final class ExtendableElementTest extends TestCase
{
    use SchemaValidationTestTrait;
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = ExtendableElement::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 2) . '/resources/xml/ssp_ExtendableElement.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $dummyDocument1 = DOMDocumentFactory::fromString(
            '<ssp:Chunk xmlns:ssp="urn:x-simplesamlphp:namespace">some</ssp:Chunk>',
        );
        $dummyDocument2 = DOMDocumentFactory::fromString(
            '<dummy:Chunk xmlns:dummy="urn:custom:dummy">some</dummy:Chunk>',
        );

        /** @var \DOMElement $dummyElement1 */
        $dummyElement1 = $dummyDocument1->documentElement;
        /** @var \DOMElement $dummyElement2 */
        $dummyElement2 = $dummyDocument2->documentElement;

        $extendableElement = new ExtendableElement(
            [
                new Chunk($dummyElement1),
                new Chunk($dummyElement2),
            ],
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($extendableElement),
        );
    }


    /**
     */
    public function testMarshallingWithExcludedElement(): void
    {
        $dummyDocument1 = DOMDocumentFactory::fromString(
            '<ssp:Chunk xmlns:ssp="urn:x-simplesamlphp:namespace">some</ssp:Chunk>',
        );
        $dummyDocument2 = DOMDocumentFactory::fromString(
            '<dummy:Chunk xmlns:dummy="urn:custom:dummy">some</dummy:Chunk>',
        );
        $dummyDocument3 = DOMDocumentFactory::fromString(
            '<other:Chunk xmlns:other="urn:custom:other">some</other:Chunk>',
        );

        /** @var \DOMElement $dummyElement1 */
        $dummyElement1 = $dummyDocument1->documentElement;
        /** @var \DOMElement $dummyElement2 */
        $dummyElement2 = $dummyDocument2->documentElement;
        /** @var \DOMElement $dummyElement3 */
        $dummyElement3 = $dummyDocument3->documentElement;

        $this->expectException(InvalidDOMElementException::class);
        new ExtendableElement(
            [
                new Chunk($dummyElement1),
                new Chunk($dummyElement2),
                new Chunk($dummyElement3),
            ],
        );
    }


    /**
     */
    public function testGetChildElementsFromXML(): void
    {
        /** @var \DOMElement $element */
        $element = self::$xmlRepresentation->documentElement;

        $elt = ExtendableElement::fromXML($element);
        /** @var \SimpleSAML\XML\Chunk[] $elements */
        $elements = $elt->getElements();

        $this->assertCount(2, $elements);
        $this->assertEquals($elements[0]->getNamespaceURI(), 'urn:x-simplesamlphp:namespace');
        $this->assertEquals($elements[0]->getPrefix(), 'ssp');
        $this->assertEquals($elements[0]->getLocalName(), 'Chunk');
        $this->assertEquals($elements[1]->getNamespaceURI(), 'urn:custom:dummy');
        $this->assertEquals($elements[1]->getPrefix(), 'dummy');
        $this->assertEquals($elements[1]->getLocalName(), 'Chunk');
    }
}
