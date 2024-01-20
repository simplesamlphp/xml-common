<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\Exception\MissingAttributeException;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\XML\ChunkTest
 *
 * @covers \SimpleSAML\XML\TestUtils\SerializableElementTestTrait
 * @covers \SimpleSAML\XML\Chunk
 *
 * @package simplesamlphp\xml-common
 */
final class ChunkTest extends TestCase
{
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = Chunk::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 2) . '/resources/xml/ssp_Element.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        /** @var \DOMElement $xml */
        $xml = self::$xmlRepresentation->documentElement;
        $chunk = new Chunk($xml);

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($chunk),
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        /** @var \DOMElement $xml */
        $xml = self::$xmlRepresentation->documentElement;
        $chunk = new Chunk($xml);

        $this->assertEquals($chunk->getLocalName(), 'Element');
        $this->assertEquals($chunk->getNamespaceURI(), 'urn:x-simplesamlphp:namespace');
        $this->assertEquals($chunk->getprefix(), 'ssp');
        $this->assertEquals($chunk->getQualifiedName(), 'ssp:Element');
        $this->assertFalse($chunk->isEmptyElement());

        // Get mandatory attributes
        $this->assertEquals(2, $chunk::getIntegerAttribute($xml, 'integer'));
        $this->assertEquals(false, $chunk::getBooleanAttribute($xml, 'boolean'));
        $this->assertEquals('text', $chunk::getAttribute($xml, 'text'));

        // Get optional attributes
        $this->assertEquals('text', $chunk::getOptionalAttribute($xml, 'text'));
        $this->assertFalse($chunk::getOptionalBooleanAttribute($xml, 'boolean'));
        $this->assertEquals(2, $chunk::getOptionalIntegerAttribute($xml, 'integer'));

        // Get optional non-existing attributes
        $this->assertNull($chunk::getOptionalAttribute($xml, 'non-existing'));
        $this->assertNull($chunk::getOptionalBooleanAttribute($xml, 'non-existing'));
        $this->assertNull($chunk::getOptionalIntegerAttribute($xml, 'non-existing'));

        // Get optional non-existing attributes with default
        $this->assertEquals('other text', $chunk::getOptionalAttribute($xml, 'non-existing', 'other text'));
        $this->assertTrue($chunk::getOptionalBooleanAttribute($xml, 'non-existing', true));
        $this->assertEquals(3, $chunk::getOptionalIntegerAttribute($xml, 'non-existing', 3));

        // Get mandatory non-existing attributes
        $this->expectException(MissingAttributeException::class);
        $chunk::getAttribute($xml, 'non-existing');
        $chunk::getBooleanAttribute($xml, 'non-existing');
        $chunk::getIntegerAttribute($xml, 'non-existing');
    }
}
