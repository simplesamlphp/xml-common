<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\ExtendableAttributesElement;
use SimpleSAML\Test\XML\ExtendableAttributesTestTrait;
use SimpleSAML\Test\XML\SchemaViolationTestTrait;
use SimpleSAML\Test\XML\SerializableElementTestTrait;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\DOMDocumentFactory;

use function dirname;

/**
 * Class \SimpleSAML\XML\ExtendableAttributesTraitTest
 *
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
        $this->schema = dirname(dirname(__FILE__)) . '/resources/schemas/simplesamlphp.xsd';

        $this->testedClass = ExtendableAttributesElement::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(dirname(__FILE__)) . '/resources/xml/ssp_ExtendableAttributesElement.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $doc = DOMDocumentFactory::fromString('<root/>');
        $attr1 = $doc->createAttributeNS('urn:x-simplesamlphp:namespace', 'test:attr1');
        $attr1->value = 'testval1';
        $attr2 = $doc->createAttributeNS('urn:x-simplesamlphp:namespace', 'test:attr2');
        $attr2->value = 'testval2';

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
        $this->assertEquals(
            'testval1',
            $extendableAttributesElement->getAttributeNS('urn:x-simplesamlphp:namespace', 'attr1')
        );

        $attributes = $extendableAttributesElement->getAttributesNS();
        $this->assertCount(2, $attributes);
    }
}
