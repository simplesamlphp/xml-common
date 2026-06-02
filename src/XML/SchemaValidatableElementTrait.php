<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use Dom;
use Dom\XMLDocument;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Exception\IOException;
use SimpleSAML\XML\Exception\RuntimeException;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;

use function array_unique;
use function defined;
use function file_exists;
use function implode;
use function libxml_clear_errors;
use function libxml_get_errors;
use function libxml_use_internal_errors;
use function sprintf;
use function trim;

/**
 * trait class to be used by all the classes that implement the SchemaValidatableElementInterface
 *
 * @package simplesamlphp/xml-common
 */
trait SchemaValidatableElementTrait
{
    /**
     * Validate the given DOMDocument against the schema set for this element
     *
     * @param \Dom\Document $document
     * @param string|null $schemaFile
     *
     * @throws \SimpleSAML\XML\Exception\IOException
     * @throws \SimpleSAML\XMLSchema\Exception\SchemaViolationException
     */
    public static function schemaValidate(Dom\Document $document, ?string $schemaFile = null): Dom\Document
    {
        $internalErrors = libxml_use_internal_errors(true);
        libxml_clear_errors();

        try {
            if ($schemaFile === null) {
                $schemaFile = self::getSchemaFile();
            }

            if (!$document instanceof XMLDocument) {
                throw new \LogicException('Schema validation requires an instance of Dom\\XMLDocument.');
            }

            /**
             * Validates using a legacy \DOMDocument round-trip (serialize + re-parse) before running schema validation.
             * This avoids false negatives seen with Dom\Document::schemaValidate(), especially around xs:QName
             * prefix scope. Validation is performed against the exact serialized XML that would be
             * exchanged externally.
             */
            $root = $document->documentElement;
            if ($root === null) {
                throw new SchemaViolationException('The document has no document element.');
            }

            $xml = $document->saveXml($root);
            if ($xml === false || trim($xml) === '') {
                throw new SchemaViolationException('Could not serialize XML for schema validation.');
            }

            $legacy = new \DOMDocument('1.0', 'UTF-8');
            $legacy->preserveWhiteSpace = true;
            $legacy->formatOutput = false;
            $legacy->loadXML($xml);

            $result = $legacy->schemaValidate($schemaFile);

            if ($result === false) {
                $msgs = [];
                foreach (libxml_get_errors() as $err) {
                    $msgs[] = trim($err->message) . ' on line ' . $err->line;
                }

                throw new SchemaViolationException(sprintf(
                    "XML schema validation errors:\n - %s",
                    implode("\n - ", array_unique($msgs)),
                ));
            }

            return $document;
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($internalErrors);
        }
    }


    /**
     * Get the schema file that can validate this element.
     * The path must be relative to the project's base directory.
     */
    public static function getSchemaFile(): string
    {
        if (!defined('static::SCHEMA')) {
            throw new RuntimeException('A SCHEMA-constant was not set on this class.');
        }
        $schemaFile = static::SCHEMA;

        Assert::true(file_exists($schemaFile), sprintf("File not found: %s", $schemaFile), IOException::class);
        return $schemaFile;
    }
}
