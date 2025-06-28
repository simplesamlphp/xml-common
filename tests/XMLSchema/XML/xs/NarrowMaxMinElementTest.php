<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Test\XML\xs;

use DOMText;
use PHPUnit\Framework\Attributes\{CoversClass, Group};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Attribute as XMLAttribute;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XMLSchema\Type\Builtin\{AnyURIValue, BooleanValue, IDValue, NCNameValue, StringValue, QNameValue};
use SimpleSAML\XMLSchema\Type\{BlockSetValue, FormChoiceValue, MaxOccursValue, MinOccursValue};
use SimpleSAML\XMLSchema\XML\xs\AbstractAnnotated;
use SimpleSAML\XMLSchema\XML\xs\AbstractElement;
use SimpleSAML\XMLSchema\XML\xs\AbstractLocalElement;
use SimpleSAML\XMLSchema\XML\xs\AbstractNarrowMaxMin;
use SimpleSAML\XMLSchema\XML\xs\AbstractOpenAttrs;
use SimpleSAML\XMLSchema\XML\xs\AbstractXsElement;
use SimpleSAML\XMLSchema\XML\xs\Annotation;
use SimpleSAML\XMLSchema\XML\xs\Appinfo;
use SimpleSAML\XMLSchema\XML\xs\Documentation;
use SimpleSAML\XMLSchema\XML\xs\Field;
use SimpleSAML\XMLSchema\XML\xs\FormChoiceEnum;
use SimpleSAML\XMLSchema\XML\xs\Keyref;
use SimpleSAML\XMLSchema\XML\xs\LocalSimpleType;
use SimpleSAML\XMLSchema\XML\xs\NarrowMaxMinElement;
use SimpleSAML\XMLSchema\XML\xs\Restriction;
use SimpleSAML\XMLSchema\XML\xs\Selector;

use function dirname;
use function strval;

/**
 * Tests for xs:narrowMaxMinElement
 *
 * @package simplesamlphp/xml-common
 */
#[Group('xs')]
#[CoversClass(NarrowMaxMinElement::class)]
#[CoversClass(AbstractNarrowMaxMin::class)]
#[CoversClass(AbstractLocalElement::class)]
#[CoversClass(AbstractElement::class)]
#[CoversClass(AbstractAnnotated::class)]
#[CoversClass(AbstractOpenAttrs::class)]
#[CoversClass(AbstractXsElement::class)]
final class NarrowMaxMinElementTest extends TestCase
{
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = NarrowMaxMinElement::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 4) . '/resources/xml/xs/narrowMaxMinElement.xml',
        );
    }


    // test marshalling


    /**
     * Test creating an NarrowMaxMinElement object from scratch.
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
        $langattr = new XMLAttribute(C::NS_XML, 'xml', 'lang', StringValue::fromString('nl'));

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
            $langattr,
            AnyURIValue::fromString('urn:x-simplesamlphp:source'),
            [$attr1],
        );
        $documentation2 = new Documentation(
            $otherDocumentationDocument->childNodes,
            $langattr,
            AnyURIValue::fromString('urn:x-simplesamlphp:source'),
            [$attr2],
        );

        $annotation = new Annotation(
            [$appinfo1, $appinfo2],
            [$documentation1, $documentation2],
            IDValue::fromString('phpunit_annotation'),
            [$attr3],
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
            annotation: $annotation,
            id: IDValue::fromString('phpunit_localElement'),
            namespacedAttributes: [$attr4],
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($narrowMaxMinElement),
        );
    }
}
