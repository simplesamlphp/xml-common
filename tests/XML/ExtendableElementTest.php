<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SchemaValidationTestTrait;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\XML\ExtendableElementTest
 *
 * @package simplesamlphp\xml-common
 */
#[CoversClass(SchemaValidationTestTrait::class)]
#[CoversClass(SerializableElementTestTrait::class)]
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
        $dummyDocument1 = DOMDocumentFactory::fromString('<ssp:Chunk xmlns:ssp="urn:x-simplesamlphp:namespace">some</ssp:Chunk>');
        $dummyDocument2 = DOMDocumentFactory::fromString('<dummy:Chunk xmlns:dummy="urn:custom:dummy">some</dummy:Chunk>');

        /** @var \DOMElement $dummyElement1 */
        $dummyElement1 = $dummyDocument1->documentElement;
        /** @var \DOMElement $dummyElement2 */
        $dummyElement2 = $dummyDocument2->documentElement;

        $extendableElement = new ExtendableElement(
            [
                new Chunk($dummyElement1),
                new Chunk($dummyElement2),
            ],
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($extendableElement),
        );
    }
}
