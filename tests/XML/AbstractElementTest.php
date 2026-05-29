<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\Helper\Element;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XMLSchema\Exception\MissingAttributeException;
use SimpleSAML\XMLSchema\Type\BooleanValue;
use SimpleSAML\XMLSchema\Type\IntegerValue;
use SimpleSAML\XMLSchema\Type\StringValue;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\XML\AbstractElementTest
 *
 * @package simplesamlphp\xml-common
 */
#[CoversClass(AbstractElement::class)]
final class AbstractElementTest extends TestCase
{
    use SerializableElementTestTrait;


    public static function setUpBeforeClass(): void
    {
        self::$testedClass = Element::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 2) . '/resources/xml/ssp_Element.xml',
        );
    }


    public function testMarshalling(): void
    {
        $element = new Element(
            IntegerValue::fromString('2'),
            BooleanValue::fromString('false'),
            StringValue::fromString('text'),
            StringValue::fromString('otherText'),
        );

        $representationRoot = self::$xmlRepresentation->documentElement;
        $this->assertInstanceOf(\Dom\Element::class, $representationRoot);

        $expectedXml = self::$xmlRepresentation->saveXml($representationRoot);
        $this->assertNotSame('', $expectedXml);
        /** @var non-empty-string $expectedXml */

        $actualXml = strval($element);
        $this->assertNotSame('', $actualXml);
        /** @var non-empty-string $actualXml */

        $expectedDoc = DOMDocumentFactory::fromString($expectedXml);
        $actualDoc = DOMDocumentFactory::fromString($actualXml);

        $expectedRoot = $expectedDoc->documentElement;
        $this->assertInstanceOf(\Dom\Element::class, $expectedRoot);

        $actualRoot = $actualDoc->documentElement;
        $this->assertInstanceOf(\Dom\Element::class, $actualRoot);

        $this->assertSame(
            $expectedRoot->C14N(),
            $actualRoot->C14N(),
        );
    }


    public function testUnmarshalling(): void
    {
        $elt = self::$xmlRepresentation->documentElement;
        $this->assertInstanceOf(\Dom\Element::class, $elt);

        $element = Element::fromXML($elt);

        $this->assertEquals('2', $element->getInteger());
        $this->assertEquals('false', $element->getBoolean());
        $this->assertEquals('text', $element->getString());
        $this->assertEquals('otherText', $element->getOtherString());
    }


    public function testGetAttribute(): void
    {
        $xml = self::$xmlRepresentation->documentElement;
        $this->assertInstanceOf(\Dom\Element::class, $xml);

        // Get mandatory attributes
        $this->assertEquals('text', Element::getAttribute($xml, 'text', StringValue::class));
        $this->assertEquals('otherText', Element::getAttribute($xml, 'otherText', StringValue::class));
        $this->assertEquals('false', Element::getAttribute($xml, 'boolean', BooleanValue::class));
        $this->assertEquals('2', Element::getAttribute($xml, 'integer', IntegerValue::class));

        // Get optional attributes
        $this->assertEquals('text', Element::getOptionalAttribute($xml, 'text', StringValue::class));
        $this->assertEquals('otherText', Element::getOptionalAttribute($xml, 'otherText', StringValue::class));
        $this->assertEquals('false', Element::getOptionalAttribute($xml, 'boolean', BooleanValue::class));
        $this->assertEquals('2', Element::getOptionalAttribute($xml, 'integer', IntegerValue::class));

        // Get optional non-existing attributes
        $this->assertNull(Element::getOptionalAttribute($xml, 'non-existing'));
        $this->assertNull(Element::getOptionalAttribute($xml, 'non-existing', StringValue::class));
        $this->assertNull(Element::getOptionalAttribute($xml, 'non-existing', IntegerValue::class));

        // Get optional non-existing attributes with default
        $this->assertNull(Element::getOptionalAttribute($xml, 'non-existing', StringValue::class, null));
        $this->assertEquals(
            'other text',
            Element::getOptionalAttribute(
                $xml,
                'non-existing',
                StringValue::class,
                StringValue::fromString('other text'),
            ),
        );
        $this->assertEquals(
            'true',
            Element::getOptionalAttribute(
                $xml,
                'non-existing',
                BooleanValue::class,
                BooleanValue::fromString('true'),
            ),
        );
        $this->assertEquals(
            '3',
            Element::getOptionalAttribute(
                $xml,
                'non-existing',
                IntegerValue::class,
                IntegerValue::fromString('3'),
            ),
        );

        // Get mandatory non-existing attributes
        $this->expectException(MissingAttributeException::class);
        Element::getAttribute($xml, 'non-existing');
        Element::getAttribute($xml, 'non-existing', BooleanValue::class);
        Element::getAttribute($xml, 'non-existing', IntegerValue::class);
    }


    public function testGetAttributeThrowsExceptionOnMissingAttribute(): void
    {
        $xml = self::$xmlRepresentation->documentElement;
        $this->assertInstanceOf(\Dom\Element::class, $xml);

        $xml = clone $xml;
        $xml->removeAttribute('text');

        $this->expectException(MissingAttributeException::class);
        Element::fromXML($xml);
    }


    public function testGetBooleanAttributeThrowsExceptionOnMissingAttribute(): void
    {
        $xml = self::$xmlRepresentation->documentElement;
        $this->assertInstanceOf(\Dom\Element::class, $xml);

        $xml = clone $xml;
        $xml->removeAttribute('boolean');

        $this->expectException(MissingAttributeException::class);
        Element::fromXML($xml);
    }


    public function testGetIntegerAttributeThrowsExceptionOnMissingAttribute(): void
    {
        $xml = self::$xmlRepresentation->documentElement;
        $this->assertInstanceOf(\Dom\Element::class, $xml);

        $xml = clone $xml;
        $xml->removeAttribute('integer');

        $this->expectException(MissingAttributeException::class);
        Element::fromXML($xml);
    }
}
