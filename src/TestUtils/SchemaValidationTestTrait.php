<?php

declare(strict_types=1);

namespace SimpleSAML\XML\TestUtils;

use DOMDocument;
use PHPUnit\Framework\Attributes\Depends;
use SimpleSAML\XML\DOMDocumentFactory;

use function class_exists;

/**
 * Test for AbstractElement classes to perform schema validation tests.
 *
 * @package simplesamlphp\xml-common
 */
trait SchemaValidationTestTrait
{
    /** @var class-string */
    protected static string $testedClass;

    /** @var string */
    protected static string $schemaFile;

    /** @var \DOMDocument */
    protected static DOMDocument $xmlRepresentation;


    /**
     * Test schema validation.
     */
    #[Depends('testSerialization')]
    public function testSchemaValidation(): void
    {
        if (!class_exists(self::$testedClass)) {
            $this->markTestSkipped(
                'Unable to run ' . self::class . '::testSchemaValidation(). Please set ' . self::class
                . ':$testedClass to a class-string representing the XML-class being tested',
            );
        } elseif (empty(self::$schemaFile)) {
            $this->markTestSkipped(
                'Unable to run ' . self::class . '::testSchemaValidation(). Please set ' . self::class
                . ':$schema to point to a schema file',
            );
        } elseif (empty(self::$xmlRepresentation)) {
            $this->markTestSkipped(
                'Unable to run ' . self::class . '::testSchemaValidation(). Please set ' . self::class
                . ':$xmlRepresentation to a DOMDocument representing the XML-class being tested',
            );
        } else {
            // Validate before serialization
            DOMDocumentFactory::schemaValidation(self::$xmlRepresentation->saveXML(), self::$schemaFile);

            // Perform serialization
            $class = self::$testedClass::fromXML(self::$xmlRepresentation->documentElement);
            $serializedClass = $class->toXML();

            // Validate after serialization
            DOMDocumentFactory::schemaValidation($serializedClass->ownerDocument->saveXML(), self::$schemaFile);

            // If we got this far and no exceptions were thrown, consider this test passed!
            $this->addToAssertionCount(1);
        }
    }

    abstract public function testSerialization(): void;
}
