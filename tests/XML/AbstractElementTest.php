<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Element;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\Exception\MissingAttributeException;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\XML\AbstractElementTest
 *
 * @covers \SimpleSAML\XML\TestUtils\SerializableElementTestTrait
 * @covers \SimpleSAML\XML\AbstractElement
 *
 * @package simplesamlphp\xml-common
 */
final class AbstractElementTest extends TestCase
{
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = Element::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 2) . '/resources/xml/ssp_Element.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $element = new Element(2, false, 'text');

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
        $element = Element::fromXML(self::$xmlRepresentation->documentElement);

        $this->assertEquals(2, $element->getInteger());
        $this->assertEquals(false, $element->getBoolean());
        $this->assertEquals('text', $element->getString());
    }


    /**
     */
    public function testGetAttribute(): void
    {
        $xml = self::$xmlRepresentation->documentElement;

        // Get mandatory attributes
        $this->assertEquals('text', Element::getAttribute($xml, 'text'));
        $this->assertFalse(Element::getBooleanAttribute($xml, 'boolean'));
        $this->assertEquals(2, Element::getIntegerAttribute($xml, 'integer'));

        // Get optional attributes
        $this->assertEquals('text', Element::getOptionalAttribute($xml, 'text'));
        $this->assertFalse(Element::getOptionalBooleanAttribute($xml, 'boolean'));
        $this->assertEquals(2, Element::getOptionalIntegerAttribute($xml, 'integer'));

        // Get optional non-existing attributes
        $this->assertNull(Element::getOptionalAttribute($xml, 'non-existing'));
        $this->assertNull(Element::getOptionalBooleanAttribute($xml, 'non-existing'));
        $this->assertNull(Element::getOptionalIntegerAttribute($xml, 'non-existing'));

        // Get optional non-existing attributes with default
        $this->assertEquals('other text', Element::getOptionalAttribute($xml, 'non-existing', 'other text'));
        $this->assertTrue(Element::getOptionalBooleanAttribute($xml, 'non-existing', true));
        $this->assertEquals(3, Element::getOptionalIntegerAttribute($xml, 'non-existing', 3));

        // Get mandatory non-existing attributes
        $this->expectException(MissingAttributeException::class);
        Element::getAttribute($xml, 'non-existing');
        Element::getBooleanAttribute($xml, 'non-existing');
        Element::getIntegerAttribute($xml, 'non-existing');
    }


    /**
     */
    public function testGetAttributeThrowsExceptionOnMissingAttribute(): void
    {
        $xml = clone self::$xmlRepresentation->documentElement;
        $xml->removeAttribute('text');

        $this->expectException(MissingAttributeException::class);
        Element::fromXML($xml);
    }


    /**
     */
    public function testGetBooleanAttributeThrowsExceptionOnMissingAttribute(): void
    {
        $xml = clone self::$xmlRepresentation->documentElement;
        $xml->removeAttribute('boolean');

        $this->expectException(MissingAttributeException::class);
        Element::fromXML($xml);
    }


    /**
     */
    public function testGetIntegerAttributeThrowsExceptionOnMissingAttribute(): void
    {
        $xml = clone self::$xmlRepresentation->documentElement;
        $xml->removeAttribute('integer');

        $this->expectException(MissingAttributeException::class);
        Element::fromXML($xml);
    }
}
