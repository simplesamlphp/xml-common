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
use SimpleSAML\XMLSchema\Type\NCNameValue;
use SimpleSAML\XMLSchema\Type\QNameValue;
use SimpleSAML\XMLSchema\Type\Schema\BlockSetValue;
use SimpleSAML\XMLSchema\Type\Schema\DerivationSetValue;
use SimpleSAML\XMLSchema\Type\Schema\FormChoiceValue;
use SimpleSAML\XMLSchema\Type\Schema\MaxOccursValue;
use SimpleSAML\XMLSchema\Type\Schema\MinOccursValue;
use SimpleSAML\XMLSchema\Type\Schema\NamespaceListValue;
use SimpleSAML\XMLSchema\Type\Schema\ProcessContentsValue;
use SimpleSAML\XMLSchema\Type\Schema\SimpleDerivationSetValue;
use SimpleSAML\XMLSchema\Type\StringValue;
use SimpleSAML\XMLSchema\XML\AbstractOpenAttrs;
use SimpleSAML\XMLSchema\XML\AbstractXsElement;
use SimpleSAML\XMLSchema\XML\All;
use SimpleSAML\XMLSchema\XML\Annotation;
use SimpleSAML\XMLSchema\XML\AnyAttribute;
use SimpleSAML\XMLSchema\XML\Constants\NS;
use SimpleSAML\XMLSchema\XML\Documentation;
use SimpleSAML\XMLSchema\XML\Enumeration\DerivationControlEnum;
use SimpleSAML\XMLSchema\XML\Enumeration\FormChoiceEnum;
use SimpleSAML\XMLSchema\XML\Enumeration\ProcessContentsEnum;
use SimpleSAML\XMLSchema\XML\Field;
use SimpleSAML\XMLSchema\XML\Keyref;
use SimpleSAML\XMLSchema\XML\LocalAttribute;
use SimpleSAML\XMLSchema\XML\LocalSimpleType;
use SimpleSAML\XMLSchema\XML\NamedAttributeGroup;
use SimpleSAML\XMLSchema\XML\NamedGroup;
use SimpleSAML\XMLSchema\XML\NarrowMaxMinElement;
use SimpleSAML\XMLSchema\XML\Redefine;
use SimpleSAML\XMLSchema\XML\ReferencedAttributeGroup;
use SimpleSAML\XMLSchema\XML\ReferencedGroup;
use SimpleSAML\XMLSchema\XML\Restriction;
use SimpleSAML\XMLSchema\XML\Selector;
use SimpleSAML\XMLSchema\XML\TopLevelComplexType;
use SimpleSAML\XMLSchema\XML\TopLevelSimpleType;
use SimpleSAML\XMLSchema\XML\XsList;

use function dirname;
use function strval;

/**
 * Tests for xs:redefine
 *
 * @package simplesamlphp/xml-common
 */
#[Group('xs')]
#[CoversClass(Redefine::class)]
#[CoversClass(AbstractOpenAttrs::class)]
#[CoversClass(AbstractXsElement::class)]
final class RedefineTest extends TestCase
{
    use SchemaValidationTestTrait;
    use SerializableElementTestTrait;
    use TestContainerTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = Redefine::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/xs/redefine.xml',
        );

        self::instantiateTestContainer();
    }


    // test marshalling


    /**
     * Test creating an Redefine object from scratch.
     */
    public function testMarshalling(): void
    {
        $simpleTypeDocument = DOMDocumentFactory::create();
        $simpleTypeText = new DOMText('SimpleType');
        $simpleTypeDocument->appendChild($simpleTypeText);

        $complexTypeDocument = DOMDocumentFactory::create();
        $complexTypeText = new DOMText('ComplexType');
        $complexTypeDocument->appendChild($complexTypeText);

        $groupDocument = DOMDocumentFactory::create();
        $groupText = new DOMText('Group');
        $groupDocument->appendChild($groupText);

        $attributeGroupDocument = DOMDocumentFactory::create();
        $attributeGroupText = new DOMText('AttributeGroup');
        $attributeGroupDocument->appendChild($attributeGroupText);

        $lang = LangValue::fromString('nl');

        $documentation1 = new Documentation(
            $simpleTypeDocument->childNodes,
            $lang,
            AnyURIValue::fromString('urn:x-simplesamlphp:source'),
            [self::$testContainer->getXMLAttribute(2)],
        );
        $documentation2 = new Documentation(
            $complexTypeDocument->childNodes,
            $lang,
            AnyURIValue::fromString('urn:x-simplesamlphp:source'),
            [self::$testContainer->getXMLAttribute(2)],
        );
        $documentation3 = new Documentation(
            $groupDocument->childNodes,
            $lang,
            AnyURIValue::fromString('urn:x-simplesamlphp:source'),
            [self::$testContainer->getXMLAttribute(2)],
        );
        $documentation4 = new Documentation(
            $attributeGroupDocument->childNodes,
            $lang,
            AnyURIValue::fromString('urn:x-simplesamlphp:source'),
            [self::$testContainer->getXMLAttribute(2)],
        );

        $annotation1 = new Annotation(
            [],
            [$documentation1],
            IDValue::fromString('phpunit_annotation1'),
            [self::$testContainer->getXMLAttribute(1)],
        );

        $annotation2 = new Annotation(
            [],
            [$documentation2],
            IDValue::fromString('phpunit_annotation2'),
            [self::$testContainer->getXMLAttribute(1)],
        );

        $annotation3 = new Annotation(
            [],
            [$documentation3],
            IDValue::fromString('phpunit_annotation3'),
            [self::$testContainer->getXMLAttribute(1)],
        );

        $annotation4 = new Annotation(
            [],
            [$documentation4],
            IDValue::fromString('phpunit_annotation4'),
            [self::$testContainer->getXMLAttribute(1)],
        );

        $restriction = new Restriction(
            null,
            [],
            QNameValue::fromString('{http://www.w3.org/2001/XMLSchema}xs:nonNegativeInteger'),
        );


        // TopLevelSimpleType
        $localSimpleType = new LocalSimpleType(
            $restriction,
        );

        $xsList = new XsList(
            $localSimpleType,
            QNameValue::fromString('{http://www.w3.org/2001/XMLSchema}xs:integer'),
        );

        $topLevelSimpleType = new TopLevelSimpleType(
            $xsList,
            NCNameValue::fromString('phpunit'),
            SimpleDerivationSetValue::fromString('#all'),
            null,
            IDValue::fromString('phpunit_simpleType'),
            [self::$testContainer->getXMLAttribute(4)],
        );

        // TopLevelComplexType
        $anyAttribute1 = new AnyAttribute(
            NamespaceListValue::fromString(NS::ANY),
            ProcessContentsValue::fromEnum(ProcessContentsEnum::Strict),
            null,
            IDValue::fromString('phpunit_anyattribute1'),
        );

        $referencedGroup = new ReferencedGroup(
            QNameValue::fromString("{http://www.w3.org/2001/XMLSchema}xs:nestedParticle"),
            null,
            IDValue::fromString('phpunit_group1'),
            [self::$testContainer->getXMLAttribute(4)],
        );

        $topLevelComplexType = new TopLevelComplexType(
            NCNameValue::fromString('complex'),
            BooleanValue::fromBoolean(true),
            BooleanValue::fromBoolean(false),
            DerivationSetValue::fromEnum(DerivationControlEnum::Restriction),
            DerivationSetValue::fromString('#all'),
            null, // content
            $referencedGroup,
            [
                new LocalAttribute(
                    type: QNameValue::fromString('{http://www.w3.org/2001/XMLSchema}xs:integer'),
                    name: NCNameValue::fromString('phpunit'),
                ),
                new ReferencedAttributeGroup(
                    QNameValue::fromString('{http://www.w3.org/2001/XMLSchema}xs:defRef'),
                ),
            ],
            $anyAttribute1,
            null,
            IDValue::fromString('phpunit_complexType'),
            [self::$testContainer->getXMLAttribute(4)],
        );

        // Group
        $selector = new Selector(
            StringValue::fromString('.//annotation'),
            null,
            IDValue::fromString('phpunit_selector'),
            [self::$testContainer->getXMLAttribute(4)],
        );

        $field = new Field(
            StringValue::fromString('@id'),
            null,
            IDValue::fromString('phpunit_field'),
            [self::$testContainer->getXMLAttribute(4)],
        );

        $keyref = new Keyref(
            QNameValue::fromString('{http://www.w3.org/2001/XMLSchema}xs:integer'),
            NCNameValue::fromString('phpunit_keyref'),
            $selector,
            [$field],
            null,
            IDValue::fromString('phpunit_keyref'),
            [self::$testContainer->getXMLAttribute(3)],
        );

        $narrowMaxMinElement = new NarrowMaxMinElement(
            name: NCNameValue::fromString('phpunit'),
            localType: $localSimpleType,
            identityConstraint: [$keyref],
            type: QNameValue::fromString('{http://www.w3.org/2001/XMLSchema}xs:group'),
            minOccurs: MinOccursValue::fromInteger(0),
            maxOccurs: MaxOccursValue::fromInteger(1),
            default: StringValue::fromString('1'),
            nillable: BooleanValue::fromBoolean(true),
            block: BlockSetValue::fromString('#all'),
            form: FormChoiceValue::fromEnum(FormChoiceEnum::Qualified),
            annotation: null,
            id: IDValue::fromString('phpunit_localElement'),
            namespacedAttributes: [self::$testContainer->getXMLAttribute(4)],
        );

        $all = new All(null, null, [$narrowMaxMinElement], null, IDValue::fromString('phpunit_all'));

        $namedGroup = new NamedGroup(
            $all,
            NCNameValue::fromString("dulyNoted"),
            null,
            IDValue::fromString('phpunit_group2'),
            [self::$testContainer->getXMLAttribute(4)],
        );

        // AttributeGroup
        $anyAttribute2 = new AnyAttribute(
            NamespaceListValue::fromString(NS::ANY),
            ProcessContentsValue::fromEnum(ProcessContentsEnum::Strict),
            null,
            IDValue::fromString('phpunit_anyattribute2'),
        );

        $attributeGroup = new NamedAttributeGroup(
            NCNameValue::fromString("number"),
            [
                new LocalAttribute(
                    type: QNameValue::fromString('{http://www.w3.org/2001/XMLSchema}xs:integer'),
                    name: NCNameValue::fromString('phpunit'),
                ),
                new ReferencedAttributeGroup(
                    QNameValue::fromString('{http://www.w3.org/2001/XMLSchema}xs:defRef'),
                ),
            ],
            $anyAttribute2,
            null,
            IDValue::fromString('phpunit_attributeGroup'),
            [self::$testContainer->getXMLAttribute(4)],
        );

        $redefine = new Redefine(
            AnyURIValue::fromString('https://example.org/schema.xsd'),
            IDValue::fromString('phpunit_redefine'),
            [
                $annotation1,
                $topLevelSimpleType,
                $annotation2,
                $topLevelComplexType,
                $annotation3,
                $namedGroup,
                $annotation4,
                $attributeGroup,
            ],
            [self::$testContainer->getXMLAttribute(3)],
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($redefine),
        );

        $this->assertFalse($redefine->isEmptyElement());
    }
}
