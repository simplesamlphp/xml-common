<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMDocument;
use SimpleSAML\Assert\Assert;
use SimpleSAML\Exception\IOException;
use SimpleSAML\Exception\SchemaViolationException;

use function array_unique;
use function defined;
use function file_exists;
use function implode;
use function sprintf;
use function trim;
use function libxml_get_errors;

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
     * @return void
     * @throws \SimpleSAML\XML\Exception\SchemaViolationException
     */
    public static function schemaValidate(DOMDocument $document): DOMDocument
    {
        $schemaFile = self::getSchemaFile();
        $result = $document->schemaValidate($schemaFile);

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
    }


    /**
     * Get the schema file that can validate this element.
     *
     * @return string
     */
    public static function getSchemaFile(): string
    {
        if (defined('static::SCHEMA')) {
            $schemaFile = static::SCHEMA;
        }

        Assert::true(file_exists($schemaFile), IOException::class);
        return $schemaFile;
    }
}
