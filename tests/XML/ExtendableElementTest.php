<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\ExtendableElementElement;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SchemaValidationTestTrait;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XML\Attribute;

use function dirname;

/**
 * Class \SimpleSAML\XML\ExtendableElementTest
 *
 * @covers \SimpleSAML\XML\TestUtils\SchemaValidationTestTrait
 * @covers \SimpleSAML\XML\TestUtils\SerializableElementTestTrait
 *
 * @package simplesamlphp\xml-common
 */
final class ExtendableElementTest extends TestCase
{
    use SchemaValidationTestTrait;
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$schemaFile = dirname(__FILE__, 2) . '/resources/schemas/simplesamlphp.xsd';

        self::$testedClass = ExtendableElement::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 2) . '/resources/xml/ssp_ExtendableElement.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $extendableElement = new ExtendableElement(
            [
                new Chunk(DOMDocumentFactory::fromString('<ssp:Chunk xmlns:ssp="urn:x-simplesamlphp:namespace">some</ssp:Chunk>')->documentElement),
                new Chunk(DOMDocumentFactory::fromString('<dummy:Chunk xmlns:dummy="urn:custom:dummy">some</dummy:Chunk>')->documentElement),
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
        $extendableElement = ExtendableElement::fromXML(self::$xmlRepresentation->documentElement);

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($extendableElement),
        );
    }
}
