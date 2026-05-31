<?php

declare(strict_types=1);

namespace SimpleSAML\XML\TestUtils;

use Dom;
use SimpleSAML\XML\DOMDocumentFactory;

use function class_exists;
use function strval;

/**
 * Test for Serializable XML classes to perform default serialization tests.
 *
 * @package simplesamlphp\xml-common
 * @phpstan-ignore trait.unused
 */
trait SerializableElementTestTrait
{
    /** @var class-string */
    protected static string $testedClass;

    /** @var \Dom\XMLDocument */
    protected static Dom\XMLDocument $xmlRepresentation;


    /**
     * Test creating XML from a class.
     */
    abstract public function testMarshalling(): void;


    /**
     * Test creating a class from XML.
     */
    public function testUnmarshalling(): void
    {
        if (!class_exists(self::$testedClass)) {
            $this->markTestSkipped(
                'Unable to run ' . self::class . '::testUnmarshalling(). Please set ' . self::class
                . ':$testedClass to a class-string representing the XML-class being tested',
            );
        } elseif (empty(self::$xmlRepresentation)) {
            $this->markTestSkipped(
                'Unable to run ' . self::class . '::testUnmarshalling(). Please set ' . self::class
                . ':$xmlRepresentation to a DOMDocument representing the XML-class being tested',
            );
        } else {
            $elt = self::$testedClass::fromXML(self::$xmlRepresentation->documentElement);

            $this->assertXmlStringEquals(
                self::$xmlRepresentation->saveXml(self::$xmlRepresentation->documentElement),
                strval($elt),
            );
        }
    }


    /**
     * Test serialization / unserialization.
     */
    public function testSerialization(): void
    {
        if (!class_exists(self::$testedClass)) {
            $this->markTestSkipped(
                'Unable to run ' . self::class . '::testSerialization(). Please set ' . self::class
                . ':$testedClass to a class-string representing the XML-class being tested',
            );
        } elseif (empty(self::$xmlRepresentation)) {
            $this->markTestSkipped(
                'Unable to run ' . self::class . '::testSerialization(). Please set ' . self::class
                . ':$xmlRepresentation to a DOMDocument representing the XML-class being tested',
            );
        } else {
            $this->assertXmlStringEquals(
                self::$xmlRepresentation->saveXml(self::$xmlRepresentation->documentElement),
                strval(unserialize(serialize(self::$testedClass::fromXML(self::$xmlRepresentation->documentElement)))),
            );
        }
    }


    private function assertXmlStringEquals(string $expectedXml, string $actualXml): void
    {
        $expectedDoc = DOMDocumentFactory::fromString($expectedXml);
        $actualDoc = DOMDocumentFactory::fromString($actualXml);

        $this->assertNotNull($expectedDoc->documentElement);
        $this->assertNotNull($actualDoc->documentElement);

        $this->assertEquals(
            $expectedDoc->documentElement->C14N(),
            $actualDoc->documentElement->C14N(),
        );
    }
}
