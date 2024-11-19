<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMDocument;
use RuntimeException;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Exception\IOException;
use SimpleSAML\XML\Exception\UnparseableXMLException;

use function defined;
use function file_get_contents;
use function libxml_clear_errors;
use function libxml_get_last_error;
use function libxml_set_external_entity_loader;
use function libxml_use_internal_errors;
use function sprintf;

/**
 * @package simplesamlphp/xml-common
 */
final class DOMDocumentFactory
{
    /**
     * @param string $xml
     * @param non-empty-string $xml
     *
     * @return \DOMDocument
     */
    public static function fromString(string $xml): DOMDocument
    {
        libxml_set_external_entity_loader(null);
        Assert::notWhitespaceOnly($xml);

        $internalErrors = libxml_use_internal_errors(true);
        libxml_clear_errors();

        $domDocument = self::create();
        /** @TODO: LIBXML_NO_XXE is available as of PHP 8.4 */
        $options = LIBXML_NONET | LIBXML_PARSEHUGE /* | LIBXML_NO_XXE */;
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
        error_clear_last();
        $xml = @file_get_contents($file);
        if ($xml === false) {
            $e = error_get_last();
            $error = $e['message'] ?? "Check that the file exists and can be read.";

            throw new IOException("File '$file' was not loaded;  $error");
        }

        Assert::notWhitespaceOnly($xml, sprintf('File "%s" does not have content', $file), RuntimeException::class);
        return static::fromString($xml);
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
}
