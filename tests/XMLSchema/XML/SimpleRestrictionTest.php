<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Test\XML;

use DOMText;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XML\TestUtils\TestContainerTestTrait;
use SimpleSAML\XML\Type\LangValue;
use SimpleSAML\XMLSchema\Type\AnyURIValue;
use SimpleSAML\XMLSchema\Type\BooleanValue;
use SimpleSAML\XMLSchema\Type\IDValue;
use SimpleSAML\XMLSchema\Type\NCNameValue;
use SimpleSAML\XMLSchema\Type\NonNegativeIntegerValue;
use SimpleSAML\XMLSchema\Type\PositiveIntegerValue;
use SimpleSAML\XMLSchema\Type\QNameValue;
use SimpleSAML\XMLSchema\Type\Schema\NamespaceListValue;
use SimpleSAML\XMLSchema\Type\Schema\ProcessContentsValue;
use SimpleSAML\XMLSchema\Type\Schema\WhiteSpaceValue;
use SimpleSAML\XMLSchema\Type\StringValue;
use SimpleSAML\XMLSchema\XML\AbstractAnnotated;
use SimpleSAML\XMLSchema\XML\AbstractOpenAttrs;
use SimpleSAML\XMLSchema\XML\AbstractRestrictionType;
use SimpleSAML\XMLSchema\XML\AbstractSimpleRestrictionType;
use SimpleSAML\XMLSchema\XML\AbstractXsElement;
use SimpleSAML\XMLSchema\XML\Annotation;
use SimpleSAML\XMLSchema\XML\AnyAttribute;
use SimpleSAML\XMLSchema\XML\Constants\NS;
use SimpleSAML\XMLSchema\XML\Documentation;
use SimpleSAML\XMLSchema\XML\Enumeration;
use SimpleSAML\XMLSchema\XML\Enumeration\ProcessContentsEnum;
use SimpleSAML\XMLSchema\XML\Enumeration\WhiteSpaceEnum;
use SimpleSAML\XMLSchema\XML\FractionDigits;
use SimpleSAML\XMLSchema\XML\Length;
use SimpleSAML\XMLSchema\XML\LocalAttribute;
use SimpleSAML\XMLSchema\XML\LocalSimpleType;
use SimpleSAML\XMLSchema\XML\MaxExclusive;
use SimpleSAML\XMLSchema\XML\MaxInclusive;
use SimpleSAML\XMLSchema\XML\MaxLength;
use SimpleSAML\XMLSchema\XML\MinExclusive;
use SimpleSAML\XMLSchema\XML\MinInclusive;
use SimpleSAML\XMLSchema\XML\MinLength;
use SimpleSAML\XMLSchema\XML\Pattern;
use SimpleSAML\XMLSchema\XML\ReferencedAttributeGroup;
use SimpleSAML\XMLSchema\XML\Restriction;
use SimpleSAML\XMLSchema\XML\SimpleRestriction;
use SimpleSAML\XMLSchema\XML\TotalDigits;
use SimpleSAML\XMLSchema\XML\WhiteSpace;

use function dirname;
use function strval;

/**
 * Tests for xs:restriction
 *
 * @package simplesamlphp/xml-common
 */
#[Group('xs')]
#[CoversClass(SimpleRestriction::class)]
#[CoversClass(AbstractSimpleRestrictionType::class)]
#[CoversClass(AbstractRestrictionType::class)]
#[CoversClass(AbstractAnnotated::class)]
#[CoversClass(AbstractOpenAttrs::class)]
#[CoversClass(AbstractXsElement::class)]
final class SimpleRestrictionTest extends TestCase
{
    use SerializableElementTestTrait;
    use TestContainerTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = SimpleRestriction::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/xs/simpleRestriction.xml',
        );

        self::instantiateTestContainer();
    }


    // test marshalling


    /**
     * Test creating a SimpleRestriction object from scratch.
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

        $anyAttribute = new AnyAttribute(
            NamespaceListValue::fromString(NS::ANY),
            ProcessContentsValue::fromEnum(ProcessContentsEnum::Strict),
            null,
            IDValue::fromString('phpunit_anyattribute'),
        );

        $restriction = new Restriction(
            null,
            [],
            QNameValue::fromString('{http://www.w3.org/2001/XMLSchema}xs:nonNegativeInteger'),
        );

        $localSimpleType = new LocalSimpleType(
            $restriction,
            null,
            IDValue::fromString('phpunit_simpleType'),
            [self::$testContainer->getXMLAttribute(4)],
        );

        $facets = [
            new MaxExclusive(
                StringValue::fromString('1024'),
                BooleanValue::fromBoolean(true),
                null,
                IDValue::fromString('phpunit_maxexclusive'),
                [self::$testContainer->getXMLAttribute(4)],
            ),
            new MaxInclusive(
                StringValue::fromString('1024'),
                BooleanValue::fromBoolean(true),
                null,
                IDValue::fromString('phpunit_maxinclusive'),
                [self::$testContainer->getXMLAttribute(4)],
            ),
            new MinExclusive(
                StringValue::fromString('128'),
                BooleanValue::fromBoolean(true),
                null,
                IDValue::fromString('phpunit_minexclusive'),
                [self::$testContainer->getXMLAttribute(4)],
            ),
            new MinInclusive(
                StringValue::fromString('128'),
                BooleanValue::fromBoolean(true),
                null,
                IDValue::fromString('phpunit_mininclusive'),
                [self::$testContainer->getXMLAttribute(4)],
            ),
            new TotalDigits(
                PositiveIntegerValue::fromInteger(2),
                BooleanValue::fromBoolean(true),
                null,
                IDValue::fromString('phpunit_totalDigits'),
                [self::$testContainer->getXMLAttribute(4)],
            ),
            new FractionDigits(
                NonNegativeIntegerValue::fromInteger(2),
                BooleanValue::fromBoolean(true),
                null,
                IDValue::fromString('phpunit_fractionDigits'),
                [self::$testContainer->getXMLAttribute(4)],
            ),
            new Length(
                NonNegativeIntegerValue::fromInteger(512),
                BooleanValue::fromBoolean(true),
                null,
                IDValue::fromString('phpunit_length'),
                [self::$testContainer->getXMLAttribute(4)],
            ),
            new MaxLength(
                NonNegativeIntegerValue::fromInteger(1024),
                BooleanValue::fromBoolean(true),
                null,
                IDValue::fromString('phpunit_maxlength'),
                [self::$testContainer->getXMLAttribute(4)],
            ),
            new MinLength(
                NonNegativeIntegerValue::fromInteger(128),
                BooleanValue::fromBoolean(true),
                null,
                IDValue::fromString('phpunit_minlength'),
                [self::$testContainer->getXMLAttribute(4)],
            ),
            new Enumeration(
                StringValue::fromString('dummy'),
                null,
                IDValue::fromString('phpunit_enumeration'),
                [self::$testContainer->getXMLAttribute(4)],
            ),
            new WhiteSpace(
                WhiteSpaceValue::fromEnum(WhiteSpaceEnum::Collapse),
                BooleanValue::fromBoolean(true),
                null,
                IDValue::fromString('phpunit_whitespace'),
                [self::$testContainer->getXMLAttribute(4)],
            ),
            new Pattern(
                StringValue::fromString('[A-Za-z0-9]'),
                null,
                IDValue::fromString('phpunit_pattern'),
                [self::$testContainer->getXMLAttribute(4)],
            ),
        ];

        $simpleRestriction = new SimpleRestriction(
            QNameValue::fromString('{http://www.w3.org/2001/XMLSchema}xs:string'),
            $localSimpleType,
            $facets,
            [
                new LocalAttribute(
                    type: QNameValue::fromString('{http://www.w3.org/2001/XMLSchema}xs:integer'),
                    name: NCNameValue::fromString('phpunit'),
                ),
                new ReferencedAttributeGroup(
                    QNameValue::fromString('{http://www.w3.org/2001/XMLSchema}xs:defRef'),
                ),
            ],
            $anyAttribute,
            $annotation,
            IDValue::fromString('phpunit_restriction'),
            [self::$testContainer->getXMLAttribute(4)],
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($simpleRestriction),
        );
    }
}
