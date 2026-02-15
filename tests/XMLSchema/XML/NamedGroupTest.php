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
use SimpleSAML\XMLSchema\Type\QNameValue;
use SimpleSAML\XMLSchema\Type\Schema\BlockSetValue;
use SimpleSAML\XMLSchema\Type\Schema\FormChoiceValue;
use SimpleSAML\XMLSchema\Type\Schema\MaxOccursValue;
use SimpleSAML\XMLSchema\Type\Schema\MinOccursValue;
use SimpleSAML\XMLSchema\Type\StringValue;
use SimpleSAML\XMLSchema\XML\AbstractAnnotated;
use SimpleSAML\XMLSchema\XML\AbstractGroup;
use SimpleSAML\XMLSchema\XML\AbstractNamedGroup;
use SimpleSAML\XMLSchema\XML\AbstractOpenAttrs;
use SimpleSAML\XMLSchema\XML\AbstractRealGroup;
use SimpleSAML\XMLSchema\XML\AbstractXsElement;
use SimpleSAML\XMLSchema\XML\All;
use SimpleSAML\XMLSchema\XML\Annotation;
use SimpleSAML\XMLSchema\XML\Documentation;
use SimpleSAML\XMLSchema\XML\Enumeration\FormChoiceEnum;
use SimpleSAML\XMLSchema\XML\Field;
use SimpleSAML\XMLSchema\XML\Keyref;
use SimpleSAML\XMLSchema\XML\LocalSimpleType;
use SimpleSAML\XMLSchema\XML\NamedGroup;
use SimpleSAML\XMLSchema\XML\NarrowMaxMinElement;
use SimpleSAML\XMLSchema\XML\Restriction;
use SimpleSAML\XMLSchema\XML\Selector;

use function dirname;
use function strval;

/**
 * Tests for xs:group
 *
 * @package simplesamlphp/xml-common
 */
#[Group('xs')]
#[CoversClass(NamedGroup::class)]
#[CoversClass(AbstractNamedGroup::class)]
#[CoversClass(AbstractRealGroup::class)]
#[CoversClass(AbstractGroup::class)]
#[CoversClass(AbstractAnnotated::class)]
#[CoversClass(AbstractOpenAttrs::class)]
#[CoversClass(AbstractXsElement::class)]
final class NamedGroupTest extends TestCase
{
    use SerializableElementTestTrait;
    use TestContainerTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = NamedGroup::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/xs/namedGroup.xml',
        );

        self::instantiateTestContainer();
    }


    // test marshalling


    /**
     * Test creating an NamedGroup object from scratch.
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
            annotation: $annotation,
            id: IDValue::fromString('phpunit_localElement'),
            namespacedAttributes: [self::$testContainer->getXMLAttribute(4)],
        );

        $all = new All(null, null, [$narrowMaxMinElement], null, IDValue::fromString('phpunit_all'));

        $namedGroup = new NamedGroup(
            $all,
            NCNameValue::fromString("dulyNoted"),
            $annotation,
            IDValue::fromString('phpunit_group'),
            [self::$testContainer->getXMLAttribute(4)],
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($namedGroup),
        );
    }
}
