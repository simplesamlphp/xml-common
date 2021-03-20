<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use DOMDocument;
use PHPUnit\Framework\TestCase;

/**
 * Test for AbstractSerializableXML classes to perform default serialization tests.
 *
 * @runTestsInSeparateProcesses
 * @package simplesamlphp\xml-common
 */
abstract class SerializableXMLTest extends TestCase
{
    /** @var class-string */
    protected static string $element;

    /** @var \DOMDocument */
    protected static DOMDocument $xmlDocument;

    /** @var array */
    protected static array $arrayDocument;


    /**
     * Test serialization / unserialization.
     */
    public function testSerialization(): void
    {
        $element = static::$element;
        $document = static::$xmlDocument;

        if ($element === null || !class_exists($element)) {
            $this->markTestSkipped('Unable to run ' . static::class . '::testSerialization(). Please set ' . static::class . ':$element to a class-string representing the XML-class being tested');
        } elseif ($document === null) {
            $this->markTestSkipped('Unable to run ' . static::class . '::testSerialization(). Please set ' . static::class . ':$xmlDocument to a DOMDocument representing the XML-class being tested');
        } else {
            $this->assertEquals(
                $document->saveXML($document->documentElement),
                strval(unserialize(serialize($element::fromXML($document->documentElement))))
            );
        }
    }


    /**
     * Test arrayization / de-arrayization
     */
    public function testArrayization(): void
    {
        $element = static::$element;
        $document = static::$arrayDocument;

        if ($element === null || !class_exists($element)) {
            $this->markTestSkipped('Unable to run ' . static::class . '::testArrayization(). Please set ' . static::class . ':$element to a class-string representing the XML-class being tested');
        } elseif ($document === null) {
            $this->markTestSkipped('Unable to run ' . static::class . '::testArrayization(). Please set ' . static::class . ':$arrayDocument to an array representing the XML-class being tested');
        } else {
            $this->assertEquals(
                $document,
                $element::fromArray($document)->toArray(),
            );
        }
    }
}
