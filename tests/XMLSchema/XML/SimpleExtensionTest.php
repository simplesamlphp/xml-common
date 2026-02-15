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
use SimpleSAML\XMLSchema\Type\IDValue;
use SimpleSAML\XMLSchema\Type\NCNameValue;
use SimpleSAML\XMLSchema\Type\QNameValue;
use SimpleSAML\XMLSchema\Type\Schema\NamespaceListValue;
use SimpleSAML\XMLSchema\Type\Schema\ProcessContentsValue;
use SimpleSAML\XMLSchema\XML\AbstractAnnotated;
use SimpleSAML\XMLSchema\XML\AbstractExtensionType;
use SimpleSAML\XMLSchema\XML\AbstractOpenAttrs;
use SimpleSAML\XMLSchema\XML\AbstractSimpleExtensionType;
use SimpleSAML\XMLSchema\XML\AbstractXsElement;
use SimpleSAML\XMLSchema\XML\Annotation;
use SimpleSAML\XMLSchema\XML\AnyAttribute;
use SimpleSAML\XMLSchema\XML\Constants\NS;
use SimpleSAML\XMLSchema\XML\Documentation;
use SimpleSAML\XMLSchema\XML\Enumeration\ProcessContentsEnum;
use SimpleSAML\XMLSchema\XML\LocalAttribute;
use SimpleSAML\XMLSchema\XML\ReferencedAttributeGroup;
use SimpleSAML\XMLSchema\XML\SimpleExtension;

use function dirname;
use function strval;

/**
 * Tests for xs:extension
 *
 * @package simplesamlphp/xml-common
 */
#[Group('xs')]
#[CoversClass(SimpleExtension::class)]
#[CoversClass(AbstractSimpleExtensionType::class)]
#[CoversClass(AbstractExtensionType::class)]
#[CoversClass(AbstractAnnotated::class)]
#[CoversClass(AbstractOpenAttrs::class)]
#[CoversClass(AbstractXsElement::class)]
final class SimpleExtensionTest extends TestCase
{
    use SerializableElementTestTrait;
    use TestContainerTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = SimpleExtension::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/xs/simpleExtension.xml',
        );

        self::instantiateTestContainer();
    }


    // test marshalling


    /**
     * Test creating a SimpleExtension object from scratch.
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

        $simpleExtension = new SimpleExtension(
            QNameValue::fromString('{http://www.w3.org/2001/XMLSchema}xs:string'),
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
            IDValue::fromString('phpunit_extension'),
            [self::$testContainer->getXMLAttribute(4)],
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($simpleExtension),
        );
    }
}
