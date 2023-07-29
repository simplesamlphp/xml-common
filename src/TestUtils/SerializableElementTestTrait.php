<?php

declare(strict_types=1);

namespace SimpleSAML\XML\TestUtils;

use DOMDocument;

use function class_exists;

/**
 * Test for Serializable XML classes to perform default serialization tests.
 *
 * @package simplesamlphp\xml-common
 */
trait SerializableElementTestTrait
{
    /** @var class-string */
    protected static string $testedClass;

    /** @var \DOMDocument */
    protected static DOMDocument $xmlRepresentation;


    /**
     * Test serialization / unserialization.
     *
     * @depends testMarshalling
     * @depends testUnmarshalling
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
            $this->assertEquals(
                self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
                strval(unserialize(serialize(self::$testedClass::fromXML(self::$xmlRepresentation->documentElement)))),
            );
        }
    }


    abstract public function testMarshalling(): void;


    abstract public function testUnmarshalling(): void;
}
