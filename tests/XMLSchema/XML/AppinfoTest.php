<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Test\XML;

use DOMText;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Attribute as XMLAttribute;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SchemaValidationTestTrait;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XMLSchema\Type\AnyURIValue;
use SimpleSAML\XMLSchema\Type\StringValue;
use SimpleSAML\XMLSchema\XML\AbstractXsElement;
use SimpleSAML\XMLSchema\XML\Appinfo;

use function dirname;
use function strval;

/**
 * Tests for xs:appinfo
 *
 * @package simplesamlphp/xml-common
 */
#[Group('xs')]
#[CoversClass(Appinfo::class)]
#[CoversClass(AbstractXsElement::class)]
final class AppinfoTest extends TestCase
{
    use SchemaValidationTestTrait;
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = Appinfo::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/xs/appinfo.xml',
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

        $attr1 = new XMLAttribute('urn:x-simplesamlphp:namespace', 'ssp', 'attr1', StringValue::fromString('value1'));
        $appinfo = new Appinfo($document->childNodes, AnyURIValue::fromString('urn:x-simplesamlphp:source'), [$attr1]);

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($appinfo),
        );

        $this->assertFalse($appinfo->isEmptyElement());
    }
}
