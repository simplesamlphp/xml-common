<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SchemaValidationTestTrait;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XML\TestUtils\TestContainerTestTrait;
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
    use TestContainerTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = Appinfo::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/xs/appinfo.xml',
        );

        self::instantiateTestContainer();
    }


    // test marshalling


    /**
     * Test creating an Appinfo object from scratch.
     */
    public function testMarshalling(): void
    {
        $appinfo = self::$testContainer->getAppinfo(1);

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($appinfo),
        );

        $this->assertFalse($appinfo->isEmptyElement());
    }
}
