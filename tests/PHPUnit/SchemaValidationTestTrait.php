<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use DOMDocument;

use function class_exists;

/**
 * Test for AbstractXMLElement classes to perform schema validation tests.
 *
 * @package simplesamlphp\xml-common
 */
trait SchemaValidationTestTrait
{
    /** @var class-string */
    protected string $testedClass;

    /** @var string */
    protected string $schema;

    /** @var \DOMDocument */
    protected DOMDocument $xmlRepresentation;


    /**
     * Test schema validation.
     */
    public function testSchemaValidation(): void
    {
        if ($this->testedClass === null || !class_exists($this->testedClass)) {
            $this->markTestSkipped(
                'Unable to run ' . self::class . '::testSchemaValidation(). Please set ' . self::class
                . ':$element to a class-string representing the XML-class being tested',
            );
        } elseif ($this->schema === null) {
            $this->markTestSkipped(
                'Unable to run ' . self::class . '::testSchemaValidation(). Please set ' . self::class
                . ':$schema to point to a schema file',
            );
        } elseif ($this->xmlRepresentation === null) {
            $this->markTestSkipped(
                'Unable to run ' . self::class . '::testSchemaValidation(). Please set ' . self::class
                . ':$xmlRepresentation to a DOMDocument representing the XML-class being tested',
            );
        } else {
            $pre = $this->xmlRepresentation->schemaValidate($this->schema);
            $this->assertTrue($pre);

            $class = $this->testedClass::fromXML($this->xmlRepresentation->documentElement);
            $serializedClass = $class->toXML();

            $post = $serializedClass->ownerDocument->schemaValidate($this->schema);
            $this->assertTrue($post);
        }
    }
}
