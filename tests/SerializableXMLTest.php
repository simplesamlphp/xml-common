<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use DOMDocument;
use PHPUnit\Framework\TestCase;

/**
 * Test for SerializableXML classes to perform default serialization tests.
 *
 * @package simplesamlphp\xml-common
 */
abstract class SerializableXMLTest extends TestCase
{
    /** @var class-string */
    protected static string $element;

    /** @var \DOMDocument */
    protected static DOMDocument $xmlRepresentation;


    /**
     * Test serialization / unserialization.
     */
    public function testSerialization(): void
    {
        $element = static::$element;
        $xmlRepresentation = static::$xmlRepresentation;

        if ($element === null || !class_exists($element)) {
            $this->markTestSkipped(
                'Unable to run ' . static::class . '::testSerialization(). Please set ' . static::class
                . ':$element to a class-string representing the XML-class being tested'
            );
        } elseif ($xmlRepresentation === null) {
            $this->markTestSkipped(
                'Unable to run ' . static::class . '::testSerialization(). Please set ' . static::class
                . ':$xmlRepresentation to a DOMDocument representing the XML-class being tested'
            );
        } else {
            $this->assertEquals(
                $xmlRepresentation->saveXML($xmlRepresentation->documentElement),
                strval(unserialize(serialize($element::fromXML($xmlRepresentation->documentElement))))
            );
        }
    }
}
