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
        $element = new Chunk(self::$xmlRepresentation->documentElement);

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($element),
        );
    }


    /**
     */
    #[Override]
    public function testUnmarshalling(): void
    {
        $element = Chunk::fromXML(self::$xmlRepresentation->documentElement);

        $this->assertEquals($element->getLocalName(), 'Element');
        $this->assertEquals($element->getNamespaceURI(), 'urn:x-simplesamlphp:namespace');
        $this->assertEquals($element->getprefix(), 'ssp');
        $this->assertEquals($element->getQualifiedName(), 'ssp:Element');
        $this->assertFalse($element->isEmptyElement());

        $xml = self::$xmlRepresentation->documentElement;

        // Get mandatory attributes
        $this->assertEquals(2, $element::getIntegerAttribute($xml, 'integer'));
        $this->assertEquals(false, $element::getBooleanAttribute($xml, 'boolean'));
        $this->assertEquals('text', $element::getAttribute($xml, 'text'));

        // Get optional attributes
        $this->assertEquals('text', $element::getOptionalAttribute($xml, 'text'));
        $this->assertFalse($element::getOptionalBooleanAttribute($xml, 'boolean'));
        $this->assertEquals(2, $element::getOptionalIntegerAttribute($xml, 'integer'));

        // Get optional non-existing attributes
        $this->assertNull($element::getOptionalAttribute($xml, 'non-existing'));
        $this->assertNull($element::getOptionalBooleanAttribute($xml, 'non-existing'));
        $this->assertNull($element::getOptionalIntegerAttribute($xml, 'non-existing'));

        // Get optional non-existing attributes with default
        $this->assertEquals('other text', $element::getOptionalAttribute($xml, 'non-existing', 'other text'));
        $this->assertTrue($element::getOptionalBooleanAttribute($xml, 'non-existing', true));
        $this->assertEquals(3, $element::getOptionalIntegerAttribute($xml, 'non-existing', 3));

        // Get mandatory non-existing attributes
        $this->expectException(MissingAttributeException::class);
        $element::getAttribute($xml, 'non-existing');
        $element::getBooleanAttribute($xml, 'non-existing');
        $element::getIntegerAttribute($xml, 'non-existing');
    }
}
