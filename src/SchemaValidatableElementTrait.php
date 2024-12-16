<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMDocument;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Exception\IOException;
use SimpleSAML\XML\Exception\SchemaViolationException;

use function array_unique;
use function defined;
use function file_exists;
use function implode;
use function libxml_get_errors;
use function restore_error_handler;
use function set_error_handler;
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
     * @return \DOMDocument
     * @throws \SimpleSAML\XML\Exception\SchemaViolationException
     */
    public static function schemaValidate(DOMDocument $document): DOMDocument
    {
        $schemaFile = self::getSchemaFile();

        // Dirty trick to catch the warnings emitted by XML-DOMs schemaValidate
        // This will turn the warning into an exception
        set_error_handler(static function (int $errno, string $errstr): never {
            throw new SchemaViolationException($errstr, $errno);
        }, E_WARNING);

        try {
            $result = $document->schemaValidate($schemaFile);
        } finally {
            // Restore the error handler, whether we throw an exception or not
            restore_error_handler();
        }

        $msgs = [];
        if ($result === false) {
            foreach (libxml_get_errors() as $err) {
                $msgs[] = trim($err->message) . ' on line ' . $err->line;
            }

            throw new SchemaViolationException(sprintf(
                "XML schema validation errors:\n - %s",
                implode("\n - ", array_unique($msgs)),
            ));
        }

        return $document;
    }


    /**
     * Get the schema file that can validate this element.
     * The path must be relative to the project's base directory.
     *
     * @return string
     */
    public static function getSchemaFile(): string
    {
        if (defined('static::SCHEMA')) {
            $schemaFile = static::SCHEMA;
        }

        Assert::true(file_exists($schemaFile), sprintf("File not found: %s", $schemaFile), IOException::class);
        return $schemaFile;
    }
}
