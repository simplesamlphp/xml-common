<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Test\XML;

use DOMText;
use PHPUnit\Framework\Attributes\{CoversClass, Group};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Attribute as XMLAttribute;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\{SchemaValidationTestTrait, SerializableElementTestTrait};
use SimpleSAML\XML\Type\LangValue;
use SimpleSAML\XMLSchema\Constants as C;
use SimpleSAML\XMLSchema\Type\{AnyURIValue, IDValue, StringValue};
use SimpleSAML\XMLSchema\Type\Schema\{MaxOccursValue, MinOccursValue, NamespaceListValue, ProcessContentsValue};
use SimpleSAML\XMLSchema\XML\Enumeration\{NamespaceEnum, ProcessContentsEnum};
use SimpleSAML\XMLSchema\XML\AbstractAnnotated;
use SimpleSAML\XMLSchema\XML\AbstractOpenAttrs;
use SimpleSAML\XMLSchema\XML\AbstractWildcard;
use SimpleSAML\XMLSchema\XML\AbstractXsElement;
use SimpleSAML\XMLSchema\XML\Annotation;
use SimpleSAML\XMLSchema\XML\Any;
use SimpleSAML\XMLSchema\XML\Appinfo;
use SimpleSAML\XMLSchema\XML\Documentation;
use SimpleSAML\XMLSchema\XML\Trait\{MaxOccursTrait, MinOccursTrait, OccursTrait};

use function dirname;
use function strval;

/**
 * Tests for xs:any
 *
 * @package simplesamlphp/xml-common
 */
#[Group('xs')]
#[CoversClass(Any::class)]
#[CoversClass(AbstractWildcard::class)]
#[CoversClass(AbstractAnnotated::class)]
#[CoversClass(AbstractOpenAttrs::class)]
#[CoversClass(AbstractXsElement::class)]
#[CoversClass(MinOccursTrait::class)]
#[CoversClass(MaxOccursTrait::class)]
#[CoversClass(OccursTrait::class)]
final class AnyTest extends TestCase
{
    use SchemaValidationTestTrait;
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = Any::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/xs/any.xml',
        );
    }


    // test marshalling


    /**
     * Test creating an AnyAttribute object from scratch.
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

        $any = new Any(
            NamespaceListValue::fromEnum(NamespaceEnum::Any),
            ProcessContentsValue::fromEnum(ProcessContentsEnum::Strict),
            $annotation,
            IDValue::fromString('phpunit_any'),
            [$attr4],
            MinOccursValue::fromInteger(1),
            MaxOccursValue::fromString('unbounded'),
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($any),
        );
    }


    /**
     * Adding an empty xs:Any element should yield an empty element.
     */
    public function testMarshallingEmptyElement(): void
    {
        $xsns = C::NS_XS;
        $any = new Any();
        $this->assertEquals(
            "<xs:any xmlns:xs=\"$xsns\"/>",
            strval($any),
        );
        $this->assertTrue($any->isEmptyElement());
    }
}
