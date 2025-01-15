<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Element;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\Exception\MissingAttributeException;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XML\Type\{IntegerValue, BooleanValue, StringValue};

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\XML\AbstractElementTest
 *
 * @package simplesamlphp\xml-common
 */
#[CoversClass(SerializableElementTestTrait::class)]
#[CoversClass(AbstractElement::class)]
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
        $element = new Element(
            IntegerValue::fromString('2'),
            BooleanValue::fromString('false'),
            StringValue::fromString('text'),
            StringValue::fromString('otherText'),
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($element),
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        /** @var \DOMElement $elt */
        $elt = self::$xmlRepresentation->documentElement;
        $element = Element::fromXML($elt);

        $this->assertEquals('2', strval($element->getInteger()));
        $this->assertEquals('false', strval($element->getBoolean()));
        $this->assertEquals('text', strval($element->getString()));
        $this->assertEquals('otherText', strval($element->getOtherString()));
    }


    /**
     */
    public function testGetAttribute(): void
    {
        /** @var \DOMElement $xml */
        $xml = self::$xmlRepresentation->documentElement;

        // Get mandatory attributes
        $this->assertEquals('text', strval(Element::getAttribute($xml, 'text', StringValue::class)));
        $this->assertEquals('otherText', strval(Element::getAttribute($xml, 'otherText', StringValue::class)));
        $this->assertEquals('false', strval(Element::getAttribute($xml, 'boolean', BooleanValue::class)));
        $this->assertEquals('2', strval(Element::getAttribute($xml, 'integer', IntegerValue::class)));

        // Get optional attributes
        $this->assertEquals('text', strval(Element::getOptionalAttribute($xml, 'text', StringValue::Class)));
        $this->assertEquals('otherText', strval(Element::getOptionalAttribute($xml, 'otherText', StringValue::class)));
        $this->assertEquals('false', strval(Element::getOptionalAttribute($xml, 'boolean', BooleanValue::class)));
        $this->assertEquals('2', strval(Element::getOptionalAttribute($xml, 'integer', IntegerValue::class)));

        // Get optional non-existing attributes
        $this->assertNull(Element::getOptionalAttribute($xml, 'non-existing'));
        $this->assertNull(Element::getOptionalAttribute($xml, 'non-existing', StringValue::class));
        $this->assertNull(Element::getOptionalAttribute($xml, 'non-existing', IntegerValue::class));

        // Get optional non-existing attributes with default
        $this->assertNull(Element::getOptionalAttribute($xml, 'non-existing', StringValue::class, null));
        $this->assertEquals('other text', strval(Element::getOptionalAttribute($xml, 'non-existing', StringValue::class, StringValue::fromString('other text'))));
        $this->assertEquals('true', strval(Element::getOptionalAttribute($xml, 'non-existing', BooleanValue::class, BooleanValue::fromString('true'))));
        $this->assertEquals('3', strval(Element::getOptionalAttribute($xml, 'non-existing', IntegerValue::class, IntegerValue::fromString('3'))));

        // Get mandatory non-existing attributes
        $this->expectException(MissingAttributeException::class);
        Element::getAttribute($xml, 'non-existing');
        Element::getAttribute($xml, 'non-existing', BooleanValue::class);
        Element::getAttribute($xml, 'non-existing', IntegerValue::class);
    }


    /**
     */
    public function testGetAttributeThrowsExceptionOnMissingAttribute(): void
    {
        /** @var \DOMElement $xml */
        $xml = self::$xmlRepresentation->documentElement;
        $xml = clone $xml;
        $xml->removeAttribute('text');

        $this->expectException(MissingAttributeException::class);
        Element::fromXML($xml);
    }


    /**
     */
    public function testGetBooleanAttributeThrowsExceptionOnMissingAttribute(): void
    {
        /** @var \DOMElement $xml */
        $xml = self::$xmlRepresentation->documentElement;
        $xml = clone $xml;
        $xml->removeAttribute('boolean');

        $this->expectException(MissingAttributeException::class);
        Element::fromXML($xml);
    }


    /**
     */
    public function testGetIntegerAttributeThrowsExceptionOnMissingAttribute(): void
    {
        /** @var \DOMElement $xml */
        $xml = self::$xmlRepresentation->documentElement;
        $xml = clone $xml;
        $xml->removeAttribute('integer');

        $this->expectException(MissingAttributeException::class);
        Element::fromXML($xml);
    }
}
