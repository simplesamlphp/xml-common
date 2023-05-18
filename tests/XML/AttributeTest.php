<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\Attribute;
use SimpleSAML\XML\TestUtils\ArrayizableElementTestTrait;

use function dirname;

/**
 * Class \SimpleSAML\XML\AttributeTest
 *
 * @covers \SimpleSAML\XML\Attribute
 *
 * @package simplesamlphp\xml-common
 */
final class AttributeTest extends TestCase
{
    use ArrayizableElementTestTrait;

    /** @var \DOMDocument */
    protected DOMDocument $xmlRepresentation;

    /**
     */
    public function setup(): void
    {
        $this->testedClass = Attribute::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromString(
            '<root xmlns:ssp="urn:x-simplesamlphp:phpunit" ssp:test1="testvalue1"/>',
        );

        $this->arrayRepresentation = [
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
            $this->arrayRepresentation,
            $extendableAttribute->toArray(),
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $extendableAttribute = Attribute::fromArray($this->arrayRepresentation);
        $this->assertEquals(
            $this->arrayRepresentation,
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
           $this->xmlRepresentation->saveXML(),
           $elt->ownerDocument->saveXML(),
        );
    }


    /**
     */
    public function testUnmarshallingXML(): void
    {
        $attr = $this->xmlRepresentation->documentElement->getAttributeNodeNS('urn:x-simplesamlphp:phpunit', 'test1');
        $extendableAttribute = Attribute::fromXML($attr);

        $this->assertEquals($extendableAttribute->toArray(), $this->arrayRepresentation);
    }
}
