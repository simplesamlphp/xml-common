<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\SerializableElementTestTrait;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\DOMDocumentFactory;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\XML\ChunkTest
 *
 * @covers \SimpleSAML\XML\Chunk
 *
 * @package simplesamlphp\xml-common
 */
final class ChunkTest extends TestCase
{
    use SerializableElementTestTrait;


    /**
     */
    public function setup(): void
    {
        $this->testedClass = Chunk::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 2) . '/resources/xml/ssp_Element.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $element = new Chunk($this->xmlRepresentation->documentElement);

        $this->assertEquals(
            $this->xmlRepresentation->saveXML($this->xmlRepresentation->documentElement),
            strval($element),
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $element = Chunk::fromXML($this->xmlRepresentation->documentElement);

        $this->assertEquals($element->getLocalName(), 'Element');
        $this->assertEquals($element->getNamespaceURI(), 'urn:x-simplesamlphp:namespace');
        $this->assertEquals($element->getprefix(), 'ssp');
        $this->assertEquals($element->getQualifiedName(), 'ssp:Element');
        $this->assertFalse($element->isEmptyElement());

        $this->assertEquals(2, $element::getIntegerAttribute($this->xmlRepresentation->documentElement, 'integer'));
        $this->assertEquals(false, $element::getBooleanAttribute($this->xmlRepresentation->documentElement, 'boolean'));
        $this->assertEquals('text', $element::getAttribute($this->xmlRepresentation->documentElement, 'text'));
    }
}
