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
use function restore_error_handler;
use function set_error_handler;
use function sprintf;
use function strpos;

/**
 * @package simplesamlphp/xml-common
 */
final class DOMDocumentFactory
{
    /**
     * Base libxml options used when parsing XML.
     *
     * Note: We add LIBXML_NO_XXE automatically when available (libxml >= 2.13.0).
     *
     * @var non-negative-int
     */
    public const int DEFAULT_OPTIONS_BASE = \LIBXML_COMPACT | \LIBXML_NOENT | \LIBXML_NONET | \LIBXML_NSCLEAN;


    /**
     * @return non-negative-int
     */
    public static function getDefaultOptions(): int
    {
        $options = self::DEFAULT_OPTIONS_BASE;

        // Add LIBXML_NO_XXE to the defaults when available (libxml >= 2.13.0)
        if (defined('LIBXML_NO_XXE')) {
            $options |= \LIBXML_NO_XXE;
        }

        return $options;
    }


    /**
     * Create a DOM XML document from an XML string.
     *
     * The input is validated to reject potentially dangerous constructs (e.g. DOCTYPE).
     * Parser warnings/notices are converted into {@see \DOMException}.
     *
     * @param non-empty-string $xml XML document as a string.
     * @param non-negative-int|null $options Libxml parser options. If {@see null}, default options will be used
     *                                      (including {@see \LIBXML_NO_XXE} when available).
     *
     * @return \Dom\XMLDocument
     *
     * @throws \SimpleSAML\Assert\AssertionFailedException If $xml is empty/whitespace-only or contains a DOCTYPE.
     * @throws \SimpleSAML\XML\Exception\RuntimeException  If dangerous XML is detected (DOCTYPE is not allowed).
     * @throws \DOMException                               If parsing emits warnings/notices or fails.
     */
    public static function fromString(
        string $xml,
        ?int $options = null,
    ): Dom\XMLDocument {
        Assert::notWhitespaceOnly($xml);
        Assert::notRegex(
            $xml,
            '/<(\s*)!(\s*)DOCTYPE/',
            'Dangerous XML detected, DOCTYPE nodes are not allowed in the XML body',
            RuntimeException::class,
        );

        $options = $options ?? self::getDefaultOptions();

        $domDocument = self::create();

        // Convert parser warnings/notices into DOMException to avoid PHP warnings leaking into test output
        set_error_handler(
        /**
         * @throws \DOMException
         */
            static function (int $severity, string $message): never {
                throw new \DOMException($message);
            },
        );

        try {
            $loaded = $domDocument->createFromString($xml, $options);
        } finally {
            restore_error_handler();
        }

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
     * Create a DOM XML document from an XML file.
     *
     * The file is read into a string and then parsed using {@see self::fromString()}.
     *
     * @param non-empty-string $file Path to the XML file.
     * @param non-negative-int|null $options Libxml parser options. If {@see null}, default options will be used
     *                                      (including {@see \LIBXML_NO_XXE} when available).
     *
     * @return \Dom\XMLDocument
     *
     * @throws \SimpleSAML\XML\Exception\IOException           If the file cannot be read.
     * @throws \SimpleSAML\Assert\AssertionFailedException     If the file content is empty/whitespace-only
     *                                                         or contains a DOCTYPE.
     * @throws \SimpleSAML\XML\Exception\RuntimeException      If dangerous XML is detected (DOCTYPE is not allowed).
     * @throws \DOMException                                   If parsing emits warnings/notices or fails.
     */
    public static function fromFile(
        string $file,
        ?int $options = null,
    ): Dom\XMLDocument {
        error_clear_last();
        $xml = @file_get_contents($file);
        if ($xml === false) {
            $e = error_get_last();
            $error = $e['message'] ?? "Check that the file exists and can be read.";

            throw new IOException("File '$file' was not loaded;  $error");
        }

        Assert::notWhitespaceOnly($xml, sprintf('File "%s" does not have content', $file), RuntimeException::class);

        return static::fromString($xml, $options);
    }


    /**
     * @param string $encoding
     */
    public static function create(string $encoding = 'UTF-8'): Dom\XMLDocument
    {
        return Dom\XMLDocument::createEmpty($encoding);
    }


    /**
     * Normalize namespace declarations in an XML document.
     *
     * This method collects namespace declarations required by prefixed elements and moves the corresponding
     * {@code xmlns:prefix} declarations to the document root, removing {@code xmlns} / {@code xmlns:*} attributes
     * from descendant elements.
     *
     * Note: this mutates the provided document and is not a substitute for XML canonicalization (C14N).
     *
     * @param \Dom\XMLDocument $doc The XML document to normalize.
     *
     * @return \Dom\XMLDocument The same document instance, potentially modified. If the document has no root element
     *                          or no namespace declarations to normalize, it is returned unchanged.
     */
    public static function normalizeDocument(Dom\XMLDocument $doc): Dom\XMLDocument
    {
        // Get the root element
        $root = $doc->documentElement;
        if ($root === null) {
            return $doc;
        }

        $xpath = XPath::getXPath($doc);
        $xmlnsAttributes = [];

        // Collect namespace declarations needed for prefixed elements in the document
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

        // Remove xmlns attributes from all elements (proper XMLNS namespace removal)
        foreach ($xpath->query('//*[namespace::*]') as $node) {
            if (!$node instanceof Dom\Element) {
                continue;
            }

            foreach ($node->attributes as $attr) {
                if ($attr->namespaceURI === C::NS_XMLNS) {
                    $node->removeAttributeNS(C::NS_XMLNS, $attr->localName);
                    continue;
                }

                if (strpos($attr->nodeName, 'xmlns') === 0 || $attr->nodeName === 'xmlns') {
                    // Fallback for implementations that still expose xmlns attrs without namespaceURI
                    $node->removeAttribute($attr->nodeName);
                }
            }
        }

        // Add all collected xmlns attributes to the root element
        foreach ($xmlnsAttributes as $name => $value) {
            $root->setAttributeNS(C::NS_XMLNS, $name, $value);
        }

        return $doc;
    }


    /**
     * Resolve a namespace URI for a given prefix in the context of an element.
     *
     * The reserved prefixes {@code xml} and {@code xmlns} are mapped to their well-known namespace URIs.
     * For all other prefixes, this method inspects the in-scope namespaces of the document element.
     *
     * @param \Dom\Element $elt    An element belonging to the document whose in-scope namespaces will be consulted.
     * @param string|null $prefix  The namespace prefix to resolve. Use {@see null} to resolve the default namespace.
     *
     * @return string|null The namespace URI associated with the given prefix, or {@see null}
     *                     if the prefix is not bound.
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

        foreach ($namespaces as $ns) {
            if ($ns->prefix === $prefix) {
                return $ns->namespaceURI;
            }
        }

        return null;
    }
}
