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


    public static function setUpBeforeClass(): void
    {
        self::$testedClass = ExtendableElement::class;

        $fixturePath = dirname(__FILE__, 2) . '/resources/xml/ssp_ExtendableElement.xml';
        self::$xmlRepresentation = DOMDocumentFactory::fromFile($fixturePath);
    }


    public function testMarshalling(): void
    {
        $dummyDocument1 = DOMDocumentFactory::fromString(
            '<ssp:Chunk xmlns:ssp="urn:x-simplesamlphp:namespace">some</ssp:Chunk>',
        );
        $dummyDocument2 = DOMDocumentFactory::fromString(
            '<dummy:Chunk xmlns:dummy="urn:custom:dummy">some</dummy:Chunk>',
        );

        $dummyElement1 = $dummyDocument1->documentElement;
        $this->assertInstanceOf(\Dom\Element::class, $dummyElement1);

        $dummyElement2 = $dummyDocument2->documentElement;
        $this->assertInstanceOf(\Dom\Element::class, $dummyElement2);

        $extendableElement = new ExtendableElement(
            [
                new Chunk($dummyElement1),
                new Chunk($dummyElement2),
            ],
        );

        $representationRoot = self::$xmlRepresentation->documentElement;
        $this->assertInstanceOf(\Dom\Element::class, $representationRoot);

        $expectedXml = self::$xmlRepresentation->saveXml($representationRoot);
        $this->assertNotSame('', $expectedXml);
        /** @var non-empty-string $expectedXml */

        $actualXml = strval($extendableElement);
        $this->assertNotSame('', $actualXml);
        /** @var non-empty-string $actualXml */

        $expectedDoc = DOMDocumentFactory::fromString($expectedXml);
        $actualDoc = DOMDocumentFactory::fromString($actualXml);

        $expectedRoot = $expectedDoc->documentElement;
        $this->assertInstanceOf(\Dom\Element::class, $expectedRoot);

        $actualRoot = $actualDoc->documentElement;
        $this->assertInstanceOf(\Dom\Element::class, $actualRoot);

        $this->assertEquals(
            $expectedRoot->C14N(),
            $actualRoot->C14N(),
        );
    }


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

        $dummyElement1 = $dummyDocument1->documentElement;
        $this->assertInstanceOf(\Dom\Element::class, $dummyElement1);

        $dummyElement2 = $dummyDocument2->documentElement;
        $this->assertInstanceOf(\Dom\Element::class, $dummyElement2);

        $dummyElement3 = $dummyDocument3->documentElement;
        $this->assertInstanceOf(\Dom\Element::class, $dummyElement3);

        $this->expectException(InvalidDOMElementException::class);
        new ExtendableElement(
            [
                new Chunk($dummyElement1),
                new Chunk($dummyElement2),
                new Chunk($dummyElement3),
            ],
        );
    }


    public function testGetChildElementsFromXML(): void
    {
        $element = self::$xmlRepresentation->documentElement;
        $this->assertInstanceOf(\Dom\Element::class, $element);

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
