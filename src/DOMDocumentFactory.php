<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMDocument;
use InvalidArgumentException;
use RuntimeException;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Exception\IOException;
use SimpleSAML\XML\Exception\UnparseableXMLException;

use function defined;
use function file_get_contents;
use function is_file;
use function is_readable;
use function libxml_clear_errors;
use function libxml_disable_entity_loader;
use function libxml_get_last_error;
use function libxml_use_internal_errors;
use function sprintf;

/**
 * @package simplesamlphp/xml-common
 */
final class DOMDocumentFactory
{
    /**
     * Constructor for DOMDocumentFactory.
     * This class should never be instantiated
     */
    private function __construct()
    {
    }


    /**
     * @param string $xml
     *
     * @return \DOMDocument
     */
    public static function fromString(string $xml): DOMDocument
    {
        Assert::notWhitespaceOnly($xml);

        $internalErrors = libxml_use_internal_errors(true);
        libxml_clear_errors();

        $domDocument = self::create();
        $options = LIBXML_DTDLOAD | LIBXML_DTDATTR | LIBXML_NONET | LIBXML_PARSEHUGE;
        if (defined('LIBXML_COMPACT')) {
            $options |= LIBXML_COMPACT;
        }

        $loaded = $domDocument->loadXML($xml, $options);

        libxml_use_internal_errors($internalErrors);

        if (!$loaded) {
            $error = libxml_get_last_error();
            libxml_clear_errors();

            throw new UnparseableXMLException($error);
        }

        libxml_clear_errors();

        foreach ($domDocument->childNodes as $child) {
            if ($child->nodeType === XML_DOCUMENT_TYPE_NODE) {
                throw new RuntimeException(
                    'Dangerous XML detected, DOCTYPE nodes are not allowed in the XML body',
                );
            }
        }

        return $domDocument;
    }


    /**
     * @param string $file
     *
     * @return \DOMDocument
     */
    public static function fromFile(string $file): DOMDocument
    {
        // libxml_disable_entity_loader(true) disables \DOMDocument::load() method
        // so we need to read the content and use \DOMDocument::loadXML()
        error_clear_last();
        $xml = @file_get_contents($file);
        if ($xml === false) {
            /** @psalm-var array $e */
            $e = error_get_last();
            $error = $e['message'] ?: "Check that the file exists and can be read.";

            throw new IOException("File '$file' was not loaded;  $error");
        }

        Assert::notWhitespaceOnly($xml, sprintf('File "%s" does not have content', $file), RuntimeException::class);
        return static::fromString($xml);
    }


    /**
     * @param ?string $version
     * @param ?string $encoding
     * @return \DOMDocument
     */
    public static function create(?string $version = null, ?string $encoding = null): DOMDocument
    {
        return new DOMDocument($version ?? '1.0', $encoding ?? '');
    }
}
