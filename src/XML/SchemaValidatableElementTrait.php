<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use Dom;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Exception\IOException;
use SimpleSAML\XML\Exception\RuntimeException;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;

use function array_filter;
use function array_unique;
use function array_values;
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
     * Validate the given Dom\Document against the schema set for this element
     *
     * @param \Dom\Document $document
     * @param string|null $schemaFile
     *
     * @throws \SimpleSAML\XML\Exception\IOException
     * @throws \SimpleSAML\XMLSchema\Exception\SchemaViolationException
     * @return \Dom\Document
     */
    public static function schemaValidate(Dom\Document $document, ?string $schemaFile = null): Dom\Document
    {
        $previousUseInternalErrors = libxml_use_internal_errors(true);

        try {
            libxml_clear_errors();

            $schemaFile ??= self::getSchemaFile();

            if (!$document instanceof Dom\XMLDocument) {
                throw new \LogicException('Schema validation requires an instance of Dom\\XMLDocument.');
            }

            $root = $document->documentElement;
            if ($root === null) {
                throw new SchemaViolationException('The document has no document element.');
            }

            /**
             * Dom\Document serialization:
             * - We need the exact serialized root bytes for the legacy validator.
             * - PHPStan may not know about Dom\Document::saveXml() depending on stubs/runtime.
             *
             * @phpstan-ignore-next-line
             */
            $xmlRoot = $document->saveXml($root);
            if ($xmlRoot === false || trim($xmlRoot) === '') {
                throw new SchemaViolationException('Could not serialize XML root for schema validation.');
            }

            $domResult = self::schemaValidateWithDomDocument($document, $schemaFile);
            if ($domResult['ok'] === false) {
                throw new SchemaViolationException(sprintf(
                    "XML schema validation errors:\n - %s",
                    implode("\n - ", $domResult['errors'] ?: ['no libxml errors reported']),
                ));
            }

            return $document;
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previousUseInternalErrors);
        }
    }


    /**
     * Validate a Dom\Document instance using a provided XML schema file.
     *
     * @param \Dom\Document $document The document to validate.
     * @param string $schemaFile The XML schema file to validate against.
     *
     * @return array{
     *     ok: bool,
     *     errors: list<string>
     * }
     */
    private static function schemaValidateWithDomDocument(Dom\Document $document, string $schemaFile): array
    {
        libxml_clear_errors();

        // Must suppress warnings here in order to throw them as an error below.
        $ok = @$document->schemaValidate($schemaFile);

        return [
            'ok' => $ok,
            'errors' => $ok ? [] : self::schemaCollectLibxmlErrors('no libxml errors reported'),
        ];
    }


    /**
     * Validate a serialized XML root element using legacy DOMDocument techniques.
     *
     * @param string $xmlRoot The serialized root XML element to validate.
     * @param string $schemaFile The XML schema file to validate against.
     *
     * @return array{
     *     ok: bool,
     *     errors: list<string>
     * }
     */
    private static function schemaValidateWithLegacyDom(string $xmlRoot, string $schemaFile): array
    {
        $legacy = new \DOMDocument('1.0', 'UTF-8');
        $legacy->preserveWhiteSpace = true;
        $legacy->formatOutput = false;

        libxml_clear_errors();
        $loaded = $legacy->loadXML($xmlRoot, LIBXML_NONET);

        if ($loaded === false) {
            $parseErrors = self::schemaCollectLibxmlErrors('Unknown XML parse error.');

            throw new SchemaViolationException(sprintf(
                "Could not parse serialized XML for schema validation.\n - %s",
                implode("\n - ", $parseErrors),
            ));
        }

        libxml_clear_errors();
        $ok = $legacy->schemaValidate($schemaFile);

        return [
            'ok' => $ok,
            'errors' => $ok ? [] : self::schemaCollectLibxmlErrors('no libxml errors reported'),
        ];
    }


    /**
     * Collect and format errors reported by libxml during XML processing.
     *
     * @param string $fallback Fallback message if no errors are reported.
     *
     * @return list<string> A list of error messages.
     */
    private static function schemaCollectLibxmlErrors(string $fallback): array
    {
        $msgs = [];
        foreach (libxml_get_errors() as $err) {
            $message = trim($err->message);
            $line = $err->line;
            $msgs[] = $line > 0 ? sprintf('%s on line %d', $message, $line) : $message;
        }

        $msgs = array_values(array_unique(array_filter($msgs)));

        return $msgs === [] ? [$fallback] : $msgs;
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
