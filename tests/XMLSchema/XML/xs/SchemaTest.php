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
    StringValue,
    TokenValue,
    QNameValue,
};
use SimpleSAML\XMLSchema\Type\{
    BlockSetValue,
    DerivationSetValue,
    FormChoiceValue,
    FullDerivationSetValue,
    NamespaceListValue,
    ProcessContentsValue,
};
use SimpleSAML\XMLSchema\XML\xs\AbstractOpenAttrs;
use SimpleSAML\XMLSchema\XML\xs\AbstractXsElement;
use SimpleSAML\XMLSchema\XML\xs\Annotation;
use SimpleSAML\XMLSchema\XML\xs\DerivationControlEnum;
use SimpleSAML\XMLSchema\XML\xs\Documentation;
use SimpleSAML\XMLSchema\XML\xs\Field;
use SimpleSAML\XMLSchema\XML\xs\FormChoiceEnum;
use SimpleSAML\XMLSchema\XML\xs\Import;
use SimpleSAML\XMLSchema\XML\xs\Keyref;
use SimpleSAML\XMLSchema\XML\xs\LocalSimpleType;
use SimpleSAML\XMLSchema\XML\xs\Restriction;
use SimpleSAML\XMLSchema\XML\xs\Schema;
use SimpleSAML\XMLSchema\XML\xs\Selector;
use SimpleSAML\XMLSchema\XML\xs\TopLevelAttribute;
use SimpleSAML\XMLSchema\XML\xs\TopLevelElement;

use function dirname;
use function strval;

/**
 * Tests for xs:schema
 *
 * @package simplesamlphp/xml-common
 */
#[Group('xs')]
#[CoversClass(Schema::class)]
#[CoversClass(AbstractOpenAttrs::class)]
#[CoversClass(AbstractXsElement::class)]
final class SchemaTest extends TestCase
{
    use SchemaValidationTestTrait;
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = Schema::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 4) . '/resources/xml/xs/schema.xml',
        );
    }


    // test marshalling


    /**
     * Test creating an Schema object from scratch.
     */
    public function testMarshalling(): void
    {
        $importDocument = DOMDocumentFactory::create();
        $importText = new DOMText('Import');
        $importDocument->appendChild($importText);

        $elementDocument = DOMDocumentFactory::create();
        $elementText = new DOMText('Element');
        $elementDocument->appendChild($elementText);

        $attributeDocument = DOMDocumentFactory::create();
        $attributeText = new DOMText('Attribute');
        $attributeDocument->appendChild($attributeText);

        $attr1 = new XMLAttribute('urn:x-simplesamlphp:namespace', 'ssp', 'attr1', StringValue::fromString('value1'));
        $attr2 = new XMLAttribute('urn:x-simplesamlphp:namespace', 'ssp', 'attr2', StringValue::fromString('value2'));
        $attr3 = new XMLAttribute('urn:x-simplesamlphp:namespace', 'ssp', 'attr3', StringValue::fromString('value3'));
        $attr4 = new XMLAttribute('urn:x-simplesamlphp:namespace', 'ssp', 'attr4', StringValue::fromString('value4'));
        $langattr = new XMLAttribute(C::NS_XML, 'xml', 'lang', StringValue::fromString('nl'));

        $documentation1 = new Documentation(
            $importDocument->childNodes,
            $langattr,
            AnyURIValue::fromString('urn:x-simplesamlphp:source'),
            [$attr2],
        );
        $documentation2 = new Documentation(
            $elementDocument->childNodes,
            $langattr,
            AnyURIValue::fromString('urn:x-simplesamlphp:source'),
            [$attr2],
        );
        $documentation3 = new Documentation(
            $attributeDocument->childNodes,
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

        $annotation2 = new Annotation(
            [],
            [$documentation2],
            IDValue::fromString('phpunit_annotation2'),
            [$attr1],
        );

        $annotation3 = new Annotation(
            [],
            [$documentation3],
            IDValue::fromString('phpunit_annotation3'),
            [$attr1],
        );

        // Import
        $import = new Import(
            AnyURIValue::fromString('urn:x-simplesamlphp:namespace'),
            AnyURIValue::fromString('file:///tmp/schema.xsd'),
            null,
            IDValue::fromString('phpunit_import'),
            [$attr4],
        );

        // Element
        $restriction = new Restriction(
            null,
            [],
            QNameValue::fromString('{http://www.w3.org/2001/XMLSchema}xs:nonNegativeInteger'),
        );

        $localSimpleType = new LocalSimpleType(
            $restriction,
        );

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

        $topLevelElement = new TopLevelElement(
            name: NCNameValue::fromString('phpunit'),
            localType: $localSimpleType,
            identityConstraint: [$keyref],
            type: QNameValue::fromString('{http://www.w3.org/2001/XMLSchema}xs:group'),
            substitutionGroup: QNameValue::fromString('{http://www.w3.org/2001/XMLSchema}xs:typeDefParticle'),
            fixed: StringValue::fromString('1'),
            final: DerivationSetValue::fromEnum(DerivationControlEnum::Extension),
            block: BlockSetValue::fromString('#all'),
            annotation: null,
            id: IDValue::fromString('phpunit_localElement'),
            namespacedAttributes: [$attr4],
        );

        // Attribute
        $simpleType = new LocalSimpleType(
            $restriction,
            null,
            IDValue::fromString('phpunit_simpleType'),
            [$attr4],
        );

        $attribute = new TopLevelAttribute(
            NCNameValue::fromString('number'),
            null,
            StringValue::fromString('1'),
            null,
            $simpleType,
            null,
            IDValue::fromString('phpunit_attribute'),
            [$attr4],
        );

        $schema = new Schema(
            [
                $annotation1,
                $import,
                $annotation2,
            ],
            [
                $topLevelElement,
                $annotation3,
                $attribute,
            ],
            AnyURIValue::fromString('http://www.w3.org/2001/XMLSchema'),
            TokenValue::fromString('1.0'),
            FullDerivationSetValue::fromEnum(DerivationControlEnum::Union),
            BlockSetValue::fromString('restriction'),
            FormChoiceValue::fromEnum(FormChoiceEnum::Unqualified),
            FormChoiceValue::fromEnum(FormChoiceEnum::Unqualified),
            IDValue::fromString('phpunit_schema'),
            new XMLAttribute(C::NS_XML, 'xml', 'lang', StringValue::fromString('en')),
            [$attr3],
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($schema),
        );

        $this->assertFalse($schema->isEmptyElement());
    }
}
