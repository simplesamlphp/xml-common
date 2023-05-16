<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\ExtendableAttributesElement;
use SimpleSAML\Test\XML\ExtendableAttributesTestTrait;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SchemaValidationTestTrait;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XML\XMLAttribute;

use function dirname;

/**
 * Class \SimpleSAML\XML\ExtendableAttributesTraitTest
 *
 * @covers \SimpleSAML\XML\TestUtils\SchemaValidationTestTrait
 * @covers \SimpleSAML\XML\TestUtils\SerializableElementTestTrait
 * @covers \SimpleSAML\XML\ExtendableAttributesTrait
 *
 * @package simplesamlphp\xml-common
 */
final class ExtendableAttributesTraitTest extends TestCase
{
    use SerializableElementTestTrait;
    use SchemaValidationTestTrait;

    /**
     */
    public function setup(): void
    {
        $this->schema = dirname(__FILE__, 2) . '/resources/schemas/simplesamlphp.xsd';

        $this->testedClass = ExtendableAttributesElement::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 2) . '/resources/xml/ssp_ExtendableAttributesElement.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $attr1 = new XMLAttribute('urn:x-simplesamlphp:namespace', 'test', 'attr1', 'testval1');
        $attr2 = new XMLAttribute('urn:x-simplesamlphp:namespace', 'test', 'attr2', 'testval2');

        $extendableAttributesElement = new ExtendableAttributesElement([$attr1, $attr2]);

        $this->assertEquals(
            $this->xmlRepresentation->saveXML($this->xmlRepresentation->documentElement),
            strval($extendableAttributesElement),
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $extendableAttributesElement = ExtendableAttributesElement::fromXML($this->xmlRepresentation->documentElement);
        $this->assertTrue($extendableAttributesElement->hasAttributeNS('urn:x-simplesamlphp:namespace', 'attr1'));

        $attr = $extendableAttributesElement->getAttributeNS('urn:x-simplesamlphp:namespace', 'attr1');
        $this->assertEquals(
            'testval1',
            $attr->getAttrValue(),
        );

        $attributes = $extendableAttributesElement->getAttributesNS();
        $this->assertCount(2, $attributes);
    }
}
