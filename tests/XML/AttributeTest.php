<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use Dom;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Attribute;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\ArrayizableElementTestTrait;
use SimpleSAML\XMLSchema\Type\StringValue;

use function strval;

/**
 * Class \SimpleSAML\XML\AttributeTest
 *
 * @package simplesamlphp\xml-common
 */
#[CoversClass(Attribute::class)]
final class AttributeTest extends TestCase
{
    use ArrayizableElementTestTrait;


    /** @var \Dom\XMLDocument */
    protected static Dom\Document $xmlRepresentation;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = Attribute::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromString(
            '<root xmlns:ssp="urn:x-simplesamlphp:phpunit" ssp:test1="testvalue1"/>',
        );

        self::$arrayRepresentation = [
            'namespaceURI' => 'urn:x-simplesamlphp:phpunit',
            'namespacePrefix' => 'ssp',
            'attrName' => 'test1',
            'attrValue' => 'testvalue1',
        ];
    }


    /**
     */
    public function testMarshallingArray(): void
    {
        $extendableAttribute = new Attribute(
            'urn:x-simplesamlphp:phpunit',
            'ssp',
            'test1',
            StringValue::fromString('testvalue1'),
        );

        $this->assertEquals(
            self::$arrayRepresentation,
            $extendableAttribute->toArray(),
        );
    }


    /**
     */
    public function testUnmarshallingArray(): void
    {
        /**
         * @var array{
         *   namespaceURI: string,
         *   namespacePrefix: string|null,
         *   attrName: string,
         *   attrValue: \SimpleSAML\XMLSchema\Type\Interface\ValueTypeInterface
         * } $arrayRepresentation
         */
        $arrayRepresentation = self::$arrayRepresentation;
        $extendableAttribute = Attribute::fromArray($arrayRepresentation);
        $this->assertEquals(
            self::$arrayRepresentation,
            $extendableAttribute->toArray(),
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $extendableAttribute = new Attribute(
            'urn:x-simplesamlphp:phpunit',
            'ssp',
            'test1',
            StringValue::fromString('testvalue1'),
        );

        $doc = DOMDocumentFactory::fromString('<root />');
        /** @var \Dom\Element $docElement */
        $docElement = $doc->documentElement;

        $elt = $extendableAttribute->toXML($docElement);
        /** @var \Dom\XMLDocument $ownerDocument */
        $ownerDocument = $elt->ownerDocument;

        $this->assertStringContainsString(
            strval(self::$xmlRepresentation->saveXml()),
            strval($ownerDocument->saveXml()),
        );
    }


    /**
     */
    public function testUnmarshallingXML(): void
    {
        /** @var \Dom\Element $elt */
        $elt = self::$xmlRepresentation->documentElement;
        /** @var \Dom\Attr $attr */
        $attr = $elt->getAttributeNodeNS('urn:x-simplesamlphp:phpunit', 'test1');
        $extendableAttribute = Attribute::fromXML($attr);

        $this->assertEquals($extendableAttribute->toArray(), self::$arrayRepresentation);
    }
}
