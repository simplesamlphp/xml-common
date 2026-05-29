<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use Dom;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\Exception\IOException;
use SimpleSAML\XML\Exception\RuntimeException;
use SimpleSAML\XPath\XPath;

use function file_get_contents;
use function func_num_args;
use function sprintf;
use function strpos;

/**
 * @package simplesamlphp/xml-common
 */
final class DOMDocumentFactory
{
    /**
     * @var non-negative-int
     * TODO: Add LIBXML_NO_XXE to the defaults when libxml 2.13.0 become generally available
     */
    public const int DEFAULT_OPTIONS = \LIBXML_COMPACT | \LIBXML_NOENT | \LIBXML_NONET | \LIBXML_NSCLEAN;


    /**
     * @param string $xml
     * @param non-negative-int $options
     */
    public static function fromString(
        string $xml,
        int $options = self::DEFAULT_OPTIONS,
    ): Dom\XMLDocument {
        Assert::notWhitespaceOnly($xml);
        Assert::notRegex(
            $xml,
            '/<(\s*)!(\s*)DOCTYPE/',
            'Dangerous XML detected, DOCTYPE nodes are not allowed in the XML body',
            RuntimeException::class,
        );

        // If LIBXML_NO_XXE is available and option not set
        if (func_num_args() === 1 && defined('LIBXML_NO_XXE')) {
            $options |= \LIBXML_NO_XXE;
        }

        $domDocument = self::create();
        $loaded = $domDocument->createFromString($xml, $options);

        foreach ($domDocument->childNodes as $child) {
            Assert::false(
                $child->nodeType === \XML_DOCUMENT_TYPE_NODE,
                'Dangerous XML detected, DOCTYPE nodes are not allowed in the XML body',
                RuntimeException::class,
            );
        }

        return $loaded;
    }


    /**
     * @param string $file
     * @param non-negative-int $options
     */
    public static function fromFile(
        string $file,
        int $options = self::DEFAULT_OPTIONS,
    ): Dom\XMLDocument {
        error_clear_last();
        $xml = @file_get_contents($file);
        if ($xml === false) {
            $e = error_get_last();
            $error = $e['message'] ?? "Check that the file exists and can be read.";

            throw new IOException("File '$file' was not loaded;  $error");
        }

        Assert::notWhitespaceOnly($xml, sprintf('File "%s" does not have content', $file), RuntimeException::class);
        return (func_num_args() < 2) ? static::fromString($xml) : static::fromString($xml, $options);
    }


    /**
     * @param string $encoding
     */
    public static function create(string $encoding = 'UTF-8'): Dom\XMLDocument
    {
        return Dom\XMLDocument::createEmpty($encoding);
    }


    /**
     * @param \Dom\XMLDocument $doc
     */
    public static function normalizeDocument(Dom\XMLDocument $doc): Dom\XMLDocument
    {
        // Get the root element
        $root = $doc->documentElement;

        // Collect all xmlns attributes from the document
        $xpath = XPath::getXPath($doc);
        $xmlnsAttributes = [];

        // Register all namespaces to ensure XPath can handle them
        foreach ($xpath->query('//*[namespace::*]') as $node) {
            if ($node instanceof Dom\Element) {
                $name = 'xmlns:' . $node->prefix;
                // Both prefix and namespaceURI NULL equals the default xmlns:xml namespace
                if ($node->prefix !== null && $node->namespaceURI !== null) {
                    $xmlnsAttributes[$name] = $node->namespaceURI;
                }
            }
        }

        // If no xmlns attributes found, return early with debug info
        if (empty($xmlnsAttributes)) {
            return $doc;
        }

        // Remove xmlns attributes from all elements
        $nodes = $xpath->query('//*[namespace::*]');
        foreach ($nodes as $node) {
            if ($node instanceof Dom\Element) {
                $attributesToRemove = [];
                foreach ($node->attributes as $attr) {
                    if (strpos($attr->nodeName, 'xmlns') === 0 || $attr->nodeName === 'xmlns') {
                        $attributesToRemove[] = $attr->namespaceURI;
                    }
                }

                foreach ($attributesToRemove as $attrName) {
                    $node->removeAttribute($attrName);
                }
            }
        }

        // Add all collected xmlns attributes to the root element
        foreach ($xmlnsAttributes as $name => $value) {
            $root->setAttribute($name, $value);
        }

        // Get the normalized string
        /** @var \Dom\XMLDocument $ownerDocument */
        $ownerDocument = $root->ownerDocument;

        // Return the normalized XML
        return static::fromString($ownerDocument->saveXml($ownerDocument->documentElement));
    }


    /**
     * @param \Dom\Element $elt
     * @param string|null $prefix
     */
    public static function lookupNamespaceURI(Dom\Element $elt, ?string $prefix): ?string
    {
        // Reserved namespace, so we don't have to look for long
        if ($prefix === 'xml') {
            return C::NS_XML;
        } elseif ($prefix === 'xmlns') {
            return C::NS_XMLNS;
        }


        /** @var \Dom\NamespaceInfo[] $namespaces */
        $namespaces = $elt->ownerDocument->documentElement->getInScopeNamespaces();

        $xmlnsAttributes = [];
        foreach ($namespaces as $ns) {
            if ($ns->prefix === $prefix) {
                return $ns->namespaceURI;
            }
        }

        return null;
    }
}
