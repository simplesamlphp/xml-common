<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Test\XML;

use DOMText;
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
use SimpleSAML\XMLSchema\Type\NonNegativeIntegerValue;
use SimpleSAML\XMLSchema\XML\AbstractAnnotated;
use SimpleSAML\XMLSchema\XML\AbstractFacet;
use SimpleSAML\XMLSchema\XML\AbstractNumFacet;
use SimpleSAML\XMLSchema\XML\AbstractOpenAttrs;
use SimpleSAML\XMLSchema\XML\AbstractXsElement;
use SimpleSAML\XMLSchema\XML\Annotation;
use SimpleSAML\XMLSchema\XML\Documentation;
use SimpleSAML\XMLSchema\XML\MaxLength;

use function dirname;
use function strval;

/**
 * Tests for xs:MaxLength
 *
 * @package simplesamlphp/xml-commonw
 */
#[Group('xs')]
#[CoversClass(MaxLength::class)]
#[CoversClass(AbstractNumFacet::class)]
#[CoversClass(AbstractFacet::class)]
#[CoversClass(AbstractAnnotated::class)]
#[CoversClass(AbstractOpenAttrs::class)]
#[CoversClass(AbstractXsElement::class)]
final class MaxLengthTest extends TestCase
{
    use SchemaValidationTestTrait;
    use SerializableElementTestTrait;
    use TestContainerTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = MaxLength::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/xs/maxLength.xml',
        );

        self::instantiateTestContainer();
    }


    // test marshalling


    /**
     * Test creating an MaxLength object from scratch.
     */
    public function testMarshalling(): void
    {
        $documentationDocument = DOMDocumentFactory::create();
        $text = new DOMText('Some Documentation');
        $documentationDocument->appendChild($text);

        $otherDocumentationDocument = DOMDocumentFactory::create();
        $text = new DOMText('Other Documentation');
        $otherDocumentationDocument->appendChild($text);

        $lang = LangValue::fromString('nl');
        $appinfo1 = self::$testContainer->getAppinfo(1);
        $appinfo2 = self::$testContainer->getAppinfo(2);

        $documentation1 = new Documentation(
            $documentationDocument->childNodes,
            $lang,
            AnyURIValue::fromString('urn:x-simplesamlphp:source'),
            [self::$testContainer->getXMLAttribute(1)],
        );
        $documentation2 = new Documentation(
            $otherDocumentationDocument->childNodes,
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

        $MaxLength = new MaxLength(
            NonNegativeIntegerValue::fromInteger(1024),
            BooleanValue::fromBoolean(true),
            $annotation,
            IDValue::fromString('phpunit_maxlength'),
            [self::$testContainer->getXMLAttribute(4)],
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($MaxLength),
        );
    }
}
