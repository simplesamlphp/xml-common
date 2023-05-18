<?php

declare(strict_types=1);

namespace SimpleSAML\XML\TestUtils;

use DOMDocument;
use LibXMLError; // Officially spelled with a lower-case `l`, but that breaks composer-require-checker
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Exception\SchemaViolationException;
use XMLReader;

use function array_unique;
use function class_exists;
use function implode;
use function libxml_get_last_error;
use function libxml_use_internal_errors;
use function trim;

/**
 * Test for AbstractElement classes to perform schema validation tests.
 *
 * @package simplesamlphp\xml-common
 */
trait SchemaValidationTestTrait
{
    /** @var class-string */
    protected string $testedClass;

    /** @var string */
    protected string $schema;

    /** @var \DOMDocument|null */
    protected ?DOMDocument $xmlRepresentation = null;


    /**
     * Test schema validation.
     */
    public function testSchemaValidation(): void
    {
        if (!class_exists($this->testedClass)) {
            $this->markTestSkipped(
                'Unable to run ' . self::class . '::testSchemaValidation(). Please set ' . self::class
                . ':$testedClass to a class-string representing the XML-class being tested',
            );
        } elseif (empty($this->schema)) {
            $this->markTestSkipped(
                'Unable to run ' . self::class . '::testSchemaValidation(). Please set ' . self::class
                . ':$schema to point to a schema file',
            );
        } elseif (empty($this->xmlRepresentation)) {
            $this->markTestSkipped(
                'Unable to run ' . self::class . '::testSchemaValidation(). Please set ' . self::class
                . ':$xmlRepresentation to a DOMDocument representing the XML-class being tested',
            );
        } else {
            $predoc = XMLReader::XML($this->xmlRepresentation->saveXML());
            Assert::notFalse($predoc);

            /** @psalm-var \XMLReader $predoc */
            $pre = $this->validateDocument($predoc);
            $this->assertTrue($pre);

            $class = $this->testedClass::fromXML($this->xmlRepresentation->documentElement);
            $serializedClass = $class->toXML();

            $postdoc = XMLReader::XML($serializedClass->ownerDocument->saveXML());
            Assert::notFalse($postdoc);
            /** @psalm-var \XMLReader $postdoc */
            $post = $this->validateDocument($postdoc);
            $this->assertTrue($post);
        }
    }


    /**
     * @param \XMLReader $doc
     * @return boolean
     */
    private function validateDocument(XMLReader $xmlReader): bool
    {
        $xmlReader->setSchema($this->schema);

        libxml_use_internal_errors(true);

        $msgs = [];

        while ($xmlReader->read()) {
            if (!$xmlReader->isValid()) {
                /** @psalm-var \libXMLError|false $err */
                $err = libxml_get_last_error();
                if ($err instanceof LibXMLError) {
                    $msgs[] = trim($err->message) . ' on line ' . $err->line;
                }
            }
        }

        if ($msgs) {
            throw new SchemaViolationException(
                "XML schema validation errors:\n - " . implode("\n - ", array_unique($msgs))
            );
        }

        return true;
    }
}