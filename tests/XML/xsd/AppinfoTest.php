<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\xsd;

use DOMText;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\xsd\AbstractXsdElement;
use SimpleSAML\XML\xsd\Appinfo;
use SimpleSAML\XML\Attribute as XMLAttribute;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SchemaValidationTestTrait;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;

use function dirname;
use function strval;

/**
 * Tests for xsd:appinfo
 *
 * @package simplesamlphp/xml-common
 */
#[Group('xsd')]
#[CoversClass(Appinfo::class)]
#[CoversClass(AbstractXsdElement::class)]
final class AppinfoTest extends TestCase
{
    use SchemaValidationTestTrait;
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = Appinfo::class;

        self::$schemaFile = dirname(__FILE__, 4) . '/resources/schemas/XMLSchema.xsd';

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/xsd/appinfo.xml',
        );
    }


    // test marshalling


    /**
     * Test creating an Appinfo object from scratch.
     */
    public function testMarshalling(): void
    {
        $document = DOMDocumentFactory::create();
        $text = new DOMText('Application Information');
        $document->appendChild($text);

        $attr1 = new XMLAttribute('urn:x-simplesamlphp:namespace', 'ssp', 'attr1', 'value1');
        $appinfo = new Appinfo($document->childNodes, 'urn:x-simplesamlphp:source', [$attr1]);

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($appinfo),
        );
    }
}
