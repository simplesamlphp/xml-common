<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Attribute;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\ArrayizableElementTestTrait;

/**
 * Class \SimpleSAML\XML\AttributeTest
 *
 * @covers \SimpleSAML\XML\Attribute
 * @covers \SimpleSAML\XML\TestUtils\ArrayizableElementTestTrait
 *
 * @package simplesamlphp\xml-common
 */
final class AttributeTest extends TestCase
{
    use ArrayizableElementTestTrait;

    /** @var \DOMDocument */
    protected static DOMDocument $xmlRepresentation;


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
            'testvalue1',
        );

        $this->assertEquals(
            self::$arrayRepresentation,
            $extendableAttribute->toArray(),
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $extendableAttribute = Attribute::fromArray(self::$arrayRepresentation);
        $this->assertEquals(
            self::$arrayRepresentation,
            $extendableAttribute->toArray(),
        );
    }


    /**
     */
    public function testMarshallingXML(): void
    {
        $extendableAttribute = new Attribute(
            'urn:x-simplesamlphp:phpunit',
            'ssp',
            'test1',
            'testvalue1',
        );

        $doc = DOMDocumentFactory::fromString('<root />');
        $elt = $extendableAttribute->toXML($doc->documentElement);

        $this->assertStringContainsString(
            self::$xmlRepresentation->saveXML(),
            $elt->ownerDocument->saveXML(),
        );
    }


    /**
     */
    public function testUnmarshallingXML(): void
    {
        $attr = self::$xmlRepresentation->documentElement->getAttributeNodeNS('urn:x-simplesamlphp:phpunit', 'test1');
        $extendableAttribute = Attribute::fromXML($attr);

        $this->assertEquals($extendableAttribute->toArray(), self::$arrayRepresentation);
    }
}
