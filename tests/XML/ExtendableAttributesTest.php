<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\ExtendableAttributesElement;
use SimpleSAML\XML\Attribute;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SchemaValidationTestTrait;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;

use function dirname;

/**
 * Class \SimpleSAML\XML\ExtendableAttributesTest
 *
 * @package simplesamlphp\xml-common
 */
#[CoversClass(SchemaValidationTestTrait::class)]
#[CoversClass(SerializableElementTestTrait::class)]
final class ExtendableAttributesTest extends TestCase
{
    use SchemaValidationTestTrait;
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$schemaFile = dirname(__FILE__, 2) . '/resources/schemas/simplesamlphp.xsd';

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
                new Attribute('urn:x-simplesamlphp:namespace', 'ssp', 'attr1', 'testval1'),
                new Attribute('urn:x-simplesamlphp:namespace', 'ssp', 'attr2', 'testval2'),
                new Attribute('urn:x-simplesamlphp:namespace', 'ssp', 'attr3', 'testval3'),
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
        $this->assertEquals($attributes[0]->getAttrValue(), 'testval1');

        $this->assertEquals($attributes[1]->getNamespaceURI(), 'urn:x-simplesamlphp:namespace');
        $this->assertEquals($attributes[1]->getNamespacePrefix(), 'ssp');
        $this->assertEquals($attributes[1]->getAttrName(), 'attr2');
        $this->assertEquals($attributes[1]->getAttrValue(), 'testval2');
    }
}
