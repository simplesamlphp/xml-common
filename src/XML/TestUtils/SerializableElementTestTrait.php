<?php

declare(strict_types=1);

namespace SimpleSAML\XML\TestUtils;

use Dom;
use PHPUnit\Framework\Attributes\Depends;

use function class_exists;

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

            $this->assertEquals(
                self::$xmlRepresentation->saveXml(self::$xmlRepresentation->documentElement),
                strval($elt),
            );
        }
    }


    /**
     * Test serialization / unserialization.
     */
    #[Depends('testMarshalling')]
    #[Depends('testUnmarshalling')]
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
            $this->assertEquals(
                self::$xmlRepresentation->saveXml(self::$xmlRepresentation->documentElement),
                strval(unserialize(serialize(self::$testedClass::fromXML(self::$xmlRepresentation->documentElement)))),
            );
        }
    }
}
