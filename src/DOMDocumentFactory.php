<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMDocument;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Exception\IOException;
use SimpleSAML\XML\Exception\RuntimeException;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Exception\UnparseableXMLException;

use function array_unique;
use function file_exists;
use function file_get_contents;
use function func_num_args;
use function implode;
use function libxml_clear_errors;
use function libxml_get_errors;
use function libxml_set_external_entity_loader;
use function libxml_use_internal_errors;
use function sprintf;
use function trim;

/**
 * @package simplesamlphp/xml-common
 */
final class DOMDocumentFactory
{
    /**
     * @var non-negative-int
     * TODO: Add LIBXML_NO_XXE to the defaults when PHP 8.4.0 + libxml 2.13.0 become generally available
     */
    public const DEFAULT_OPTIONS = LIBXML_COMPACT | LIBXML_NONET | LIBXML_NSCLEAN;


    /**
     * @param string $xml
     * @param string|null $schemaFile
     * @param non-negative-int $options
     *
     * @return \DOMDocument
     */
    public static function fromString(
        string $xml,
        ?string $schemaFile = null,
        int $options = self::DEFAULT_OPTIONS,
    ): DOMDocument {
        libxml_set_external_entity_loader(null);
        Assert::notWhitespaceOnly($xml);
        Assert::notRegex(
            $xml,
            '/<(\s*)!(\s*)DOCTYPE/',
            'Dangerous XML detected, DOCTYPE nodes are not allowed in the XML body',
            RuntimeException::class,
        );

        $internalErrors = libxml_use_internal_errors(true);
        libxml_clear_errors();

        // If LIBXML_NO_XXE is available and option not set
        if (func_num_args() === 1 && defined('LIBXML_NO_XXE')) {
            $options |= LIBXML_NO_XXE;
        }

        // Perform optional schema validation
        if (!empty($schemaFile)) {
            self::schemaValidation($xml, $schemaFile, $options);
        }

        $domDocument = self::create();
        $loaded = $domDocument->loadXML($xml, $options);

        libxml_use_internal_errors($internalErrors);

        if (!$loaded) {
            $error = libxml_get_last_error();
            libxml_clear_errors();

            throw new UnparseableXMLException($error);
        }

        libxml_clear_errors();

        foreach ($domDocument->childNodes as $child) {
            Assert::false(
                $child->nodeType === XML_DOCUMENT_TYPE_NODE,
                'Dangerous XML detected, DOCTYPE nodes are not allowed in the XML body',
                RuntimeException::class,
            );
        }

        return $domDocument;
    }


    /**
     * @param string $file
     * @param string|null $schemaFile
     * @param non-negative-int $options
     *
     * @return \DOMDocument
     */
    public static function fromFile(
        string $file,
        ?string $schemaFile = null,
        int $options = self::DEFAULT_OPTIONS,
    ): DOMDocument {
        error_clear_last();
        $xml = @file_get_contents($file);
        if ($xml === false) {
            $e = error_get_last();
            $error = $e['message'] ?? "Check that the file exists and can be read.";

            throw new IOException("File '$file' was not loaded;  $error");
        }

        Assert::notWhitespaceOnly($xml, sprintf('File "%s" does not have content', $file), RuntimeException::class);
        return (func_num_args() < 3)
            ? static::fromString($xml, $schemaFile)
            : static::fromString($xml, $schemaFile, $options);
    }


    /**
     * @param string $version
     * @param string $encoding
     * @return \DOMDocument
     */
    public static function create(string $version = '1.0', string $encoding = 'UTF-8'): DOMDocument
    {
        return new DOMDocument($version, $encoding);
    }


    /**
     * Validate an XML-string against a given schema.
     *
     * @param string $xml
     * @param string $schemaFile
     * @param int $options
     *
     * @throws \SimpleSAML\XML\Exception\SchemaViolationException when validation fails.
     */
    public static function schemaValidation(
        string $xml,
        string $schemaFile,
        int $options = self::DEFAULT_OPTIONS,
    ): void {
        if (!file_exists($schemaFile)) {
            throw new IOException('File not found.');
        }

        $document = DOMDocumentFactory::fromString($xml);
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
    }
}
