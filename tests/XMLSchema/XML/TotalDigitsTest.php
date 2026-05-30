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
use SimpleSAML\XMLSchema\Type\BooleanValue;
use SimpleSAML\XMLSchema\Type\IDValue;
use SimpleSAML\XMLSchema\Type\PositiveIntegerValue;
use SimpleSAML\XMLSchema\XML\AbstractAnnotated;
use SimpleSAML\XMLSchema\XML\AbstractFacet;
use SimpleSAML\XMLSchema\XML\AbstractNumFacet;
use SimpleSAML\XMLSchema\XML\AbstractOpenAttrs;
use SimpleSAML\XMLSchema\XML\AbstractXsElement;
use SimpleSAML\XMLSchema\XML\Annotation;
use SimpleSAML\XMLSchema\XML\Documentation;
use SimpleSAML\XMLSchema\XML\TotalDigits;

use function dirname;
use function strval;

/**
 * Tests for xs:totalDigits
 *
 * @package simplesamlphp/xml-common
 */
#[Group('xs')]
#[CoversClass(TotalDigits::class)]
#[CoversClass(AbstractNumFacet::class)]
#[CoversClass(AbstractFacet::class)]
#[CoversClass(AbstractAnnotated::class)]
#[CoversClass(AbstractOpenAttrs::class)]
#[CoversClass(AbstractXsElement::class)]
final class TotalDigitsTest extends TestCase
{
    use SchemaValidationTestTrait;
    use SerializableElementTestTrait;
    use TestContainerTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = TotalDigits::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/xs/totalDigits.xml',
        );

        self::instantiateTestContainer();
    }


    // test marshalling


    /**
     * Test creating a TotalDigits object from scratch.
     */
    public function testMarshalling(): void
    {
        $documentationText = self::$testContainer->getDOMText('Some Documentation');
        $otherDocumentationText = self::$testContainer->getDOMText('Other Documentation');

        $lang = LangValue::fromString('nl');
        $appinfo1 = self::$testContainer->getAppinfo(1);
        $appinfo2 = self::$testContainer->getAppinfo(2);

        $documentation1 = new Documentation(
            $documentationText,
            $lang,
            AnyURIValue::fromString('urn:x-simplesamlphp:source'),
            [self::$testContainer->getXMLAttribute(1)],
        );
        $documentation2 = new Documentation(
            $otherDocumentationText,
            $lang,
            AnyURIValue::fromString('urn:x-simplesamlphp:source'),
            [self::$testContainer->getXMLAttribute(2)],
        );

        $annotation = new Annotation(
            [$appinfo1, $appinfo2],
            [$documentation1, $documentation2],
            IDValue::fromString('phpunit_annotation'),
            [self::$testContainer->getXMLAttribute(3)],
        );

        $totalDigits = new TotalDigits(
            PositiveIntegerValue::fromInteger(2),
            BooleanValue::fromBoolean(true),
            $annotation,
            IDValue::fromString('phpunit_totalDigits'),
            [self::$testContainer->getXMLAttribute(4)],
        );

        $expectedXml = self::$xmlRepresentation->saveXml(self::$xmlRepresentation->documentElement);
        $this->assertNotFalse($expectedXml);
        $actualXml = strval($totalDigits);

        $this->assertXmlStringEqualsXmlString($expectedXml, $actualXml);
    }
}
