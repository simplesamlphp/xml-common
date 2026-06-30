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
use SimpleSAML\XML\Type\LangValue;
use SimpleSAML\XMLSchema\Type\AnyURIValue;
use SimpleSAML\XMLSchema\XML\AbstractXsElement;
use SimpleSAML\XMLSchema\XML\Documentation;

use function dirname;
use function strval;

/**
 * Tests for xs:documentation
 *
 * @package simplesamlphp/xml-common
 */
#[Group('xs')]
#[CoversClass(Documentation::class)]
#[CoversClass(AbstractXsElement::class)]
final class DocumentationTest extends TestCase
{
    use SchemaValidationTestTrait;
    use SerializableElementTestTrait;
    use TestContainerTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = Documentation::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/xs/documentation.xml',
        );

        self::instantiateTestContainer();
    }


    // test marshalling


    /**
     * Test creating an Documentation object from scratch.
     */
    public function testMarshalling(): void
    {
        $documentationText = self::$testContainer->getDOMText('Some Documentation');
        $lang = LangValue::fromString('nl');

        $documentation = new Documentation(
            $documentationText,
            $lang,
            AnyURIValue::fromString('urn:x-simplesamlphp:source'),
            [self::$testContainer->getXMLAttribute(1)],
        );

        $expectedXml = self::$xmlRepresentation->saveXml(self::$xmlRepresentation->documentElement);
        $this->assertNotFalse($expectedXml);
        $actualXml = strval($documentation);

        $this->assertXmlStringEqualsXmlString($expectedXml, $actualXml);

        $this->assertFalse($documentation->isEmptyElement());
    }
}
