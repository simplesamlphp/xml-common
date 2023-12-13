<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

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
 * @covers \SimpleSAML\XML\TestUtils\SchemaValidationTestTrait
 * @covers \SimpleSAML\XML\TestUtils\SerializableElementTestTrait
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
            ],
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($extendableElement),
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $extendableElement = ExtendableAttributesElement::fromXML(self::$xmlRepresentation->documentElement);

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($extendableElement),
        );
    }
}
