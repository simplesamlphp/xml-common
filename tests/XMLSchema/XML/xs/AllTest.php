<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Test\XML\xs;

use DOMText;
use PHPUnit\Framework\Attributes\{CoversClass, Group};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Attribute as XMLAttribute;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\{SchemaValidationTestTrait, SerializableElementTestTrait};
use SimpleSAML\XMLSchema\Type\Builtin\{
    AnyURIValue,
    BooleanValue,
    IDValue,
    NCNameValue,
    QNameValue,
    StringValue,
};
use SimpleSAML\XMLSchema\Type\{
    BlockSetValue,
    DerivationSetValue,
    FormChoiceValue,
    MaxOccursValue,
    MinOccursValue,
    NamespaceListValue,
    ProcessContentsValue,
    SimpleDerivationSetValue,
};
use SimpleSAML\XMLSchema\XML\xs\AbstractAll;
use SimpleSAML\XMLSchema\XML\xs\AbstractOpenAttrs;
use SimpleSAML\XMLSchema\XML\xs\AbstractXsElement;
use SimpleSAML\XMLSchema\XML\xs\All;
use SimpleSAML\XMLSchema\XML\xs\Annotation;
use SimpleSAML\XMLSchema\XML\xs\AnyAttribute;
use SimpleSAML\XMLSchema\XML\xs\LocalAttribute;
use SimpleSAML\XMLSchema\XML\xs\DerivationControlEnum;
use SimpleSAML\XMLSchema\XML\xs\Documentation;
use SimpleSAML\XMLSchema\XML\xs\Field;
use SimpleSAML\XMLSchema\XML\xs\FormChoiceEnum;
use SimpleSAML\XMLSchema\XML\xs\Keyref;
use SimpleSAML\XMLSchema\XML\xs\LocalSimpleType;
use SimpleSAML\XMLSchema\XML\xs\NamespaceEnum;
use SimpleSAML\XMLSchema\XML\xs\NarrowMaxMinElement;
use SimpleSAML\XMLSchema\XML\xs\ProcessContentsEnum;
use SimpleSAML\XMLSchema\XML\xs\ReferencedAttributeGroup;
use SimpleSAML\XMLSchema\XML\xs\ReferencedGroup;
use SimpleSAML\XMLSchema\XML\xs\Restriction;
use SimpleSAML\XMLSchema\XML\xs\Selector;
use SimpleSAML\XMLSchema\XML\xs\TopLevelComplexType;
use SimpleSAML\XMLSchema\XML\xs\TopLevelSimpleType;
use SimpleSAML\XMLSchema\XML\xs\XsList;

use function dirname;
use function strval;

/**
 * Tests for xs:all
 *
 * @package simplesamlphp/xml-common
 */
#[Group('xs')]
#[CoversClass(All::class)]
#[CoversClass(AbstractAll::class)]
#[CoversClass(AbstractOpenAttrs::class)]
#[CoversClass(AbstractXsElement::class)]
final class AllTest extends TestCase
{
    use SchemaValidationTestTrait;
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = All::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 4) . '/resources/xml/xs/all.xml',
        );
    }


    // test marshalling


    /**
     * Test creating an All object from scratch.
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

        $attr1 = new XMLAttribute('urn:x-simplesamlphp:namespace', 'ssp', 'attr1', StringValue::fromString('value1'));
        $attr2 = new XMLAttribute('urn:x-simplesamlphp:namespace', 'ssp', 'attr2', StringValue::fromString('value2'));
        $attr3 = new XMLAttribute('urn:x-simplesamlphp:namespace', 'ssp', 'attr3', StringValue::fromString('value3'));
        $attr4 = new XMLAttribute('urn:x-simplesamlphp:namespace', 'ssp', 'attr4', StringValue::fromString('value4'));
        $langattr = new XMLAttribute(C::NS_XML, 'xml', 'lang', StringValue::fromString('nl'));

        $documentation1 = new Documentation(
            $simpleTypeDocument->childNodes,
            $langattr,
            AnyURIValue::fromString('urn:x-simplesamlphp:source'),
            [$attr2],
        );

        $annotation1 = new Annotation(
            [],
            [$documentation1],
            IDValue::fromString('phpunit_annotation1'),
            [$attr1],
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
            [$attr4],
        );

        // TopLevelComplexType
        $anyAttribute1 = new AnyAttribute(
            NamespaceListValue::fromEnum(NamespaceEnum::Any),
            ProcessContentsValue::fromEnum(ProcessContentsEnum::Strict),
            null,
            IDValue::fromString('phpunit_anyattribute1'),
        );

        $referencedGroup = new ReferencedGroup(
            QNameValue::fromString("{http://www.w3.org/2001/XMLSchema}xs:nestedParticle"),
            null,
            IDValue::fromString('phpunit_group1'),
            [$attr4],
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
            [$attr4],
        );

        // Group
        $selector = new Selector(
            StringValue::fromString('.//annotation'),
            null,
            IDValue::fromString('phpunit_selector'),
            [$attr4],
        );

        $field = new Field(
            StringValue::fromString('@id'),
            null,
            IDValue::fromString('phpunit_field'),
            [$attr4],
        );

        $keyref = new Keyref(
            QNameValue::fromString('{http://www.w3.org/2001/XMLSchema}xs:integer'),
            NCNameValue::fromString('phpunit_keyref'),
            $selector,
            [$field],
            null,
            IDValue::fromString('phpunit_keyref'),
            [$attr3],
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
            namespacedAttributes: [$attr4],
        );

        $all = new All(
            null,
            null,
            [$narrowMaxMinElement],
            $annotation1,
            IDValue::fromString('phpunit_all'),
            [$attr3],
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($all),
        );

        $this->assertFalse($all->isEmptyElement());
    }
}
