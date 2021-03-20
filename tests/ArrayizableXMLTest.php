<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use DOMDocument;
use PHPUnit\Framework\TestCase;

/**
 * Test for ArrayizableXML classes to perform default serialization tests.
 *
 * @package simplesamlphp\xml-common
 */
abstract class ArrayizableXMLTest extends SerializableXMLTest
{
    /** @var array */
    protected static array $arrayRepresentation;


    /**
     * Test arrayization / de-arrayization
     */
    public function testArrayization(): void
    {
        $element = static::$element;
        $arrayRepresentation = static::$arrayRepresentation;

        if ($element === null || !class_exists($element)) {
            $this->markTestSkipped(
                'Unable to run ' . static::class . '::testArrayization(). Please set ' . static::class
                . ':$element to a class-string representing the XML-class being tested'
            );
        } elseif ($arrayRepresentation === null) {
            $this->markTestSkipped(
                'Unable to run ' . static::class . '::testArrayization(). Please set ' . static::class
                . ':$arrayRepresentation to an array representing the XML-class being tested'
            );
        } else {
            $this->assertEquals(
                $arrayRepresentation,
                $element::fromArray($arrayRepresentation)->toArray(),
            );
        }
    }
}
