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
use SimpleSAML\XMLSchema\Type\Schema\MaxOccursValue;
use SimpleSAML\XMLSchema\Type\Schema\MinOccursValue;
use SimpleSAML\XMLSchema\XML\AbstractAnnotated;
use SimpleSAML\XMLSchema\XML\AbstractGroup;
use SimpleSAML\XMLSchema\XML\AbstractOpenAttrs;
use SimpleSAML\XMLSchema\XML\AbstractRealGroup;
use SimpleSAML\XMLSchema\XML\AbstractXsElement;
use SimpleSAML\XMLSchema\XML\Annotation;
use SimpleSAML\XMLSchema\XML\Documentation;
use SimpleSAML\XMLSchema\XML\ReferencedGroup;
use SimpleSAML\XMLSchema\XML\Sequence;

use function dirname;
use function strval;

/**
 * Tests for xs:sequence
 *
 * @package simplesamlphp/xml-common
 */
#[Group('xs')]
#[CoversClass(Sequence::class)]
#[CoversClass(AbstractRealGroup::class)]
#[CoversClass(AbstractGroup::class)]
#[CoversClass(AbstractAnnotated::class)]
#[CoversClass(AbstractOpenAttrs::class)]
#[CoversClass(AbstractXsElement::class)]
final class SequenceTest extends TestCase
{
    use SerializableElementTestTrait;
    use TestContainerTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = Sequence::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/xs/sequence.xml',
        );

        self::instantiateTestContainer();
    }


    // test marshalling


    /**
     * Test creating an Sequence object from scratch.
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

        $sequence = new Sequence(
            MinOccursValue::fromInteger(0),
            MaxOccursValue::fromString('unbounded'),
            [$referencedGroup],
            $annotation,
            IDValue::fromString('phpunit_sequence'),
            [self::$testContainer->getXMLAttribute(4)],
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($sequence),
        );
    }
}
