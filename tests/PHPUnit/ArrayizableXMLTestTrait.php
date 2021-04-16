<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use DOMDocument;

/**
 * Test for ArrayizableXML classes to perform default serialization tests.
 *
 * @package simplesamlphp\xml-common
 */
trait ArrayizableXMLTestTrait
{
    /** @var class-string|null */
    protected ?string $testedClass = null;

    /** @var array|null */
    protected ?array $arrayRepresentation = null;


    /**
     * Test arrayization / de-arrayization
     */
    public function testArrayization(): void
    {
        if (!class_exists($this->testedClass)) {
            $this->markTestSkipped(
                'Unable to run ' . self::class . '::testArrayization(). Please set ' . self::class
                . ':$element to a class-string representing the XML-class being tested'
            );
        } elseif ($this->arrayRepresentation === null) {
            $this->markTestSkipped(
                'Unable to run ' . self::class . '::testArrayization(). Please set ' . self::class
                . ':$arrayRepresentation to an array representing the XML-class being tested'
            );
        } else {
            $this->assertEquals(
                $this->arrayRepresentation,
                $this->testedClass::fromArray($this->arrayRepresentation)->toArray(),
            );
        }
    }
}
