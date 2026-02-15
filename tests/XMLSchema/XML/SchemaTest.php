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
use SimpleSAML\XMLSchema\Constants as C;
use SimpleSAML\XMLSchema\Type\AnyURIValue;
use SimpleSAML\XMLSchema\Type\IDValue;
use SimpleSAML\XMLSchema\Type\NCNameValue;
use SimpleSAML\XMLSchema\Type\QNameValue;
use SimpleSAML\XMLSchema\Type\Schema\BlockSetValue;
use SimpleSAML\XMLSchema\Type\Schema\DerivationSetValue;
use SimpleSAML\XMLSchema\Type\Schema\FormChoiceValue;
use SimpleSAML\XMLSchema\Type\Schema\FullDerivationSetValue;
use SimpleSAML\XMLSchema\Type\StringValue;
use SimpleSAML\XMLSchema\Type\TokenValue;
use SimpleSAML\XMLSchema\XML\AbstractOpenAttrs;
use SimpleSAML\XMLSchema\XML\AbstractXsElement;
use SimpleSAML\XMLSchema\XML\Annotation;
use SimpleSAML\XMLSchema\XML\Documentation;
use SimpleSAML\XMLSchema\XML\Enumeration\DerivationControlEnum;
use SimpleSAML\XMLSchema\XML\Enumeration\FormChoiceEnum;
use SimpleSAML\XMLSchema\XML\Field;
use SimpleSAML\XMLSchema\XML\Import;
use SimpleSAML\XMLSchema\XML\Keyref;
use SimpleSAML\XMLSchema\XML\LocalSimpleType;
use SimpleSAML\XMLSchema\XML\Restriction;
use SimpleSAML\XMLSchema\XML\Schema;
use SimpleSAML\XMLSchema\XML\Selector;
use SimpleSAML\XMLSchema\XML\TopLevelAttribute;
use SimpleSAML\XMLSchema\XML\TopLevelElement;

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
    use TestContainerTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = Schema::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/xs/schema.xml',
        );

        self::instantiateTestContainer();
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

        $lang = LangValue::fromString('nl');

        $documentation1 = new Documentation(
            $importDocument->childNodes,
            $lang,
            AnyURIValue::fromString('urn:x-simplesamlphp:source'),
            [self::$testContainer->getXMLAttribute(2)],
        );
        $documentation2 = new Documentation(
            $elementDocument->childNodes,
            $lang,
            AnyURIValue::fromString('urn:x-simplesamlphp:source'),
            [self::$testContainer->getXMLAttribute(2)],
        );
        $documentation3 = new Documentation(
            $attributeDocument->childNodes,
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

        // Import
        $import = new Import(
            AnyURIValue::fromString('urn:x-simplesamlphp:namespace'),
            AnyURIValue::fromString('file:///tmp/schema.xsd'),
            null,
            IDValue::fromString('phpunit_import'),
            [self::$testContainer->getXMLAttribute(4)],
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
            namespacedAttributes: [self::$testContainer->getXMLAttribute(4)],
        );

        // Attribute
        $simpleType = new LocalSimpleType(
            $restriction,
            null,
            IDValue::fromString('phpunit_simpleType'),
            [self::$testContainer->getXMLAttribute(4)],
        );

        $attribute = new TopLevelAttribute(
            NCNameValue::fromString('number'),
            null,
            StringValue::fromString('1'),
            null,
            $simpleType,
            null,
            IDValue::fromString('phpunit_attribute'),
            [self::$testContainer->getXMLAttribute(4)],
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
            AnyURIValue::fromString(C::NS_XS),
            TokenValue::fromString('1.0'),
            FullDerivationSetValue::fromEnum(DerivationControlEnum::Union),
            BlockSetValue::fromString('restriction'),
            FormChoiceValue::fromEnum(FormChoiceEnum::Unqualified),
            FormChoiceValue::fromEnum(FormChoiceEnum::Unqualified),
            IDValue::fromString('phpunit_schema'),
            LangValue::fromString('en'),
            [self::$testContainer->getXMLAttribute(3)],
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($schema),
        );

        $this->assertFalse($schema->isEmptyElement());
    }
}
