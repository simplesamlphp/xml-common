<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Test\XML;

use DOMText;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Attribute as XMLAttribute;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
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
use SimpleSAML\XMLSchema\XML\Appinfo;
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


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = SimpleRestriction::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/xs/simpleRestriction.xml',
        );
    }


    // test marshalling


    /**
     * Test creating a SimpleRestriction object from scratch.
     */
    public function testMarshalling(): void
    {
        $appinfoDocument = DOMDocumentFactory::create();
        $text = new DOMText('Application Information');
        $appinfoDocument->appendChild($text);

        $otherAppinfoDocument = DOMDocumentFactory::create();
        $otherText = new DOMText('Other Application Information');
        $otherAppinfoDocument->appendChild($otherText);

        $documentationDocument = DOMDocumentFactory::create();
        $text = new DOMText('Some Documentation');
        $documentationDocument->appendChild($text);

        $otherDocumentationDocument = DOMDocumentFactory::create();
        $text = new DOMText('Other Documentation');
        $otherDocumentationDocument->appendChild($text);

        $attr1 = new XMLAttribute('urn:x-simplesamlphp:namespace', 'ssp', 'attr1', StringValue::fromString('value1'));
        $attr2 = new XMLAttribute('urn:x-simplesamlphp:namespace', 'ssp', 'attr2', StringValue::fromString('value2'));
        $attr3 = new XMLAttribute('urn:x-simplesamlphp:namespace', 'ssp', 'attr3', StringValue::fromString('value3'));
        $attr4 = new XMLAttribute('urn:x-simplesamlphp:namespace', 'ssp', 'attr4', StringValue::fromString('value4'));
        $lang = LangValue::fromString('nl');

        $appinfo1 = new Appinfo(
            $appinfoDocument->childNodes,
            AnyURIValue::fromString('urn:x-simplesamlphp:source'),
            [$attr1],
        );
        $appinfo2 = new Appinfo(
            $otherAppinfoDocument->childNodes,
            AnyURIValue::fromString('urn:x-simplesamlphp:source'),
            [$attr2],
        );

        $documentation1 = new Documentation(
            $documentationDocument->childNodes,
            $lang,
            AnyURIValue::fromString('urn:x-simplesamlphp:source'),
            [$attr1],
        );
        $documentation2 = new Documentation(
            $otherDocumentationDocument->childNodes,
            $lang,
            AnyURIValue::fromString('urn:x-simplesamlphp:source'),
            [$attr2],
        );

        $annotation = new Annotation(
            [$appinfo1, $appinfo2],
            [$documentation1, $documentation2],
            IDValue::fromString('phpunit_annotation'),
            [$attr3],
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
            [$attr4],
        );

        $facets = [
            new MaxExclusive(
                StringValue::fromString('1024'),
                BooleanValue::fromBoolean(true),
                null,
                IDValue::fromString('phpunit_maxexclusive'),
                [$attr4],
            ),
            new MaxInclusive(
                StringValue::fromString('1024'),
                BooleanValue::fromBoolean(true),
                null,
                IDValue::fromString('phpunit_maxinclusive'),
                [$attr4],
            ),
            new MinExclusive(
                StringValue::fromString('128'),
                BooleanValue::fromBoolean(true),
                null,
                IDValue::fromString('phpunit_minexclusive'),
                [$attr4],
            ),
            new MinInclusive(
                StringValue::fromString('128'),
                BooleanValue::fromBoolean(true),
                null,
                IDValue::fromString('phpunit_mininclusive'),
                [$attr4],
            ),
            new TotalDigits(
                PositiveIntegerValue::fromInteger(2),
                BooleanValue::fromBoolean(true),
                null,
                IDValue::fromString('phpunit_totalDigits'),
                [$attr4],
            ),
            new FractionDigits(
                NonNegativeIntegerValue::fromInteger(2),
                BooleanValue::fromBoolean(true),
                null,
                IDValue::fromString('phpunit_fractionDigits'),
                [$attr4],
            ),
            new Length(
                NonNegativeIntegerValue::fromInteger(512),
                BooleanValue::fromBoolean(true),
                null,
                IDValue::fromString('phpunit_length'),
                [$attr4],
            ),
            new MaxLength(
                NonNegativeIntegerValue::fromInteger(1024),
                BooleanValue::fromBoolean(true),
                null,
                IDValue::fromString('phpunit_maxlength'),
                [$attr4],
            ),
            new MinLength(
                NonNegativeIntegerValue::fromInteger(128),
                BooleanValue::fromBoolean(true),
                null,
                IDValue::fromString('phpunit_minlength'),
                [$attr4],
            ),
            new Enumeration(
                StringValue::fromString('dummy'),
                null,
                IDValue::fromString('phpunit_enumeration'),
                [$attr4],
            ),
            new WhiteSpace(
                WhiteSpaceValue::fromEnum(WhiteSpaceEnum::Collapse),
                BooleanValue::fromBoolean(true),
                null,
                IDValue::fromString('phpunit_whitespace'),
                [$attr4],
            ),
            new Pattern(
                StringValue::fromString('[A-Za-z0-9]'),
                null,
                IDValue::fromString('phpunit_pattern'),
                [$attr4],
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
            [$attr4],
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($simpleRestriction),
        );
    }
}
