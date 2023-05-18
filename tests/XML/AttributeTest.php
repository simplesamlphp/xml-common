<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

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

    /**
     */
    public function setup(): void
    {
        $this->testedClass = Attribute::class;

        $this->arrayRepresentation = [
            'namespaceURI' => 'urn:x-simplesamlphp:phpunit',
            'namespacePrefix' => 'ssp',
            'attrName' => 'test1',
            'attrValue' => 'testvalue1',
        ];
    }


    /**
     */
    public function testMarshalling(): void
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
}
