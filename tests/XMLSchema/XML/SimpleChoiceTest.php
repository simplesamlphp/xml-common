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
use SimpleSAML\XMLSchema\XML\AbstractExplicitGroup;
use SimpleSAML\XMLSchema\XML\AbstractGroup;
use SimpleSAML\XMLSchema\XML\AbstractOpenAttrs;
use SimpleSAML\XMLSchema\XML\AbstractSimpleExplicitGroup;
use SimpleSAML\XMLSchema\XML\AbstractXsElement;
use SimpleSAML\XMLSchema\XML\Annotation;
use SimpleSAML\XMLSchema\XML\Documentation;
use SimpleSAML\XMLSchema\XML\ReferencedGroup;
use SimpleSAML\XMLSchema\XML\SimpleChoice;

use function dirname;
use function strval;

/**
 * Tests for xs:choice
 *
 * @package simplesamlphp/xml-common
 */
#[Group('xs')]
#[CoversClass(SimpleChoice::class)]
#[CoversClass(AbstractSimpleExplicitGroup::class)]
#[CoversClass(AbstractExplicitGroup::class)]
#[CoversClass(AbstractGroup::class)]
#[CoversClass(AbstractAnnotated::class)]
#[CoversClass(AbstractOpenAttrs::class)]
#[CoversClass(AbstractXsElement::class)]
final class SimpleChoiceTest extends TestCase
{
    use SerializableElementTestTrait;
    use TestContainerTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = SimpleChoice::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/xs/simpleChoice.xml',
        );

        self::instantiateTestContainer();
    }


    // test marshalling


    /**
     * Test creating an Choice object from scratch.
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

        $referencedGroup = new ReferencedGroup(
            QNameValue::fromString("{http://www.w3.org/2001/XMLSchema}xs:nestedParticle"),
        );

        $choice = new SimpleChoice(
            [$referencedGroup],
            $annotation,
            IDValue::fromString('phpunit_choice'),
            [self::$testContainer->getXMLAttribute(4)],
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($choice),
        );
    }


    /**
     * Creating an empty Choice element should yield an empty element.
     */
    public function testMarshallingEmptyElement(): void
    {
        $choice = new SimpleChoice();
        $this->assertEquals(
            '<xs:choice xmlns:xs="http://www.w3.org/2001/XMLSchema"/>',
            strval($choice),
        );
        $this->assertTrue($choice->isEmptyElement());
    }
}
