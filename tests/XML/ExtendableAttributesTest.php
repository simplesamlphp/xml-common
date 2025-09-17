<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\Helper\ExtendableAttributesElement;
use SimpleSAML\XML\Attribute;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SchemaValidationTestTrait;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XMLSchema\Type\StringValue;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\XML\ExtendableAttributesTest
 *
 * @package simplesamlphp\xml-common
 */
final class ExtendableAttributesTest extends TestCase
{
    use SchemaValidationTestTrait;
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = ExtendableAttributesElement::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 2) . '/resources/xml/ssp_ExtendableAttributesElement.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $extendableElement = new ExtendableAttributesElement(
            [
                new Attribute('urn:x-simplesamlphp:namespace', 'ssp', 'attr1', StringValue::fromString('testval1')),
                new Attribute('urn:x-simplesamlphp:namespace', 'ssp', 'attr2', StringValue::fromString('testval2')),
                new Attribute('urn:x-simplesamlphp:namespace', 'ssp', 'attr3', StringValue::fromString('testval3')),
            ],
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($extendableElement),
        );
    }


    /**
     */
    public function testGetAttributesNSFromXML(): void
    {
        /** @var \DOMElement $element */
        $element = self::$xmlRepresentation->documentElement;

        $elt = ExtendableAttributesElement::fromXML($element);
        $attributes = $elt->getAttributesNS();

        $this->assertCount(2, $attributes);
        $this->assertEquals($attributes[0]->getNamespaceURI(), 'urn:x-simplesamlphp:namespace');
        $this->assertEquals($attributes[0]->getNamespacePrefix(), 'ssp');
        $this->assertEquals($attributes[0]->getAttrName(), 'attr1');
        $this->assertEquals(strval($attributes[0]->getAttrValue()), 'testval1');

        $this->assertEquals($attributes[1]->getNamespaceURI(), 'urn:x-simplesamlphp:namespace');
        $this->assertEquals($attributes[1]->getNamespacePrefix(), 'ssp');
        $this->assertEquals($attributes[1]->getAttrName(), 'attr2');
        $this->assertEquals(strval($attributes[1]->getAttrValue()), 'testval2');
    }
}
