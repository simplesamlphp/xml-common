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
use SimpleSAML\XMLSchema\Type\QNameValue;
use SimpleSAML\XMLSchema\XML\AbstractAnnotated;
use SimpleSAML\XMLSchema\XML\AbstractAttributeGroup;
use SimpleSAML\XMLSchema\XML\AbstractOpenAttrs;
use SimpleSAML\XMLSchema\XML\AbstractReferencedAttributeGroup;
use SimpleSAML\XMLSchema\XML\AbstractXsElement;
use SimpleSAML\XMLSchema\XML\Annotation;
use SimpleSAML\XMLSchema\XML\Documentation;
use SimpleSAML\XMLSchema\XML\ReferencedAttributeGroup;

use function dirname;
use function strval;

/**
 * Tests for xs:attributeGroup
 *
 * @package simplesamlphp/xml-common
 */
#[Group('xs')]
#[CoversClass(ReferencedAttributeGroup::class)]
#[CoversClass(AbstractReferencedAttributeGroup::class)]
#[CoversClass(AbstractAttributeGroup::class)]
#[CoversClass(AbstractAnnotated::class)]
#[CoversClass(AbstractOpenAttrs::class)]
#[CoversClass(AbstractXsElement::class)]
final class ReferencedAttributeGroupTest extends TestCase
{
    use SerializableElementTestTrait;
    use TestContainerTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = ReferencedAttributeGroup::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/xs/referencedAttributeGroup.xml',
        );

        self::instantiateTestContainer();
    }


    // test marshalling


    /**
     * Test creating an AttributeGroup object from scratch.
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

        $attributeGroup = new ReferencedAttributeGroup(
            QNameValue::fromString("{http://www.w3.org/2001/XMLSchema}xs:occurs"),
            $annotation,
            IDValue::fromString('phpunit_attributeGroup'),
            [self::$testContainer->getXMLAttribute(4)],
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($attributeGroup),
        );

        $this->assertFalse($attributeGroup->isEmptyElement());
    }
}
