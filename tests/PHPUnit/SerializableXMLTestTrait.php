<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use DOMDocument;

/**
 * Test for SerializableXML classes to perform default serialization tests.
 *
 * @package simplesamlphp\xml-common
 */
trait SerializableXMLTestTrait
{
    /** @var class-string */
    protected string $testedClass;

    /** @var \DOMDocument */
    protected DOMDocument $xmlRepresentation;


    /**
     * Test serialization / unserialization.
     */
    public function testSerialization(): void
    {
        if (!class_exists($this->testedClass)) {
            $this->markTestSkipped(
                'Unable to run ' . self::class . '::testSerialization(). Please set ' . self::class
                . ':$element to a class-string representing the XML-class being tested'
            );
        } elseif ($this->xmlRepresentation === null) {
            $this->markTestSkipped(
                'Unable to run ' . self::class . '::testSerialization(). Please set ' . self::class
                . ':$xmlRepresentation to a DOMDocument representing the XML-class being tested'
            );
        } else {
            $this->assertEquals(
                $this->xmlRepresentation->saveXML($this->xmlRepresentation->documentElement),
                strval(unserialize(serialize($this->testedClass::fromXML($this->xmlRepresentation->documentElement))))
            );
        }
    }
}
