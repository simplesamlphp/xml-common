<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\XMLAttribute;
use SimpleSAML\XML\TestUtils\ArrayizableElementTestTrait;

use function dirname;

/**
 * Class \SimpleSAML\XML\XMLAttributeTest
 *
 * @covers \SimpleSAML\XML\XMLAttribute
 *
 * @package simplesamlphp\xml-common
 */
final class XMLAttributeTest extends TestCase
{
    use ArrayizableElementTestTrait;

    /**
     */
    public function setup(): void
    {
        $this->testedClass = XMLAttribute::class;

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
        $extendableAttribute = new XMLAttribute(
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
        $extendableAttribute = XMLAttribute::fromArray($this->arrayRepresentation);
        $this->assertEquals(
            $this->arrayRepresentation,
            $extendableAttribute->toArray(),
        );
    }
}
