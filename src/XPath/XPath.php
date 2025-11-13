<?php

declare(strict_types=1);

namespace SimpleSAML\XPath;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Constants as C_XML;
use SimpleSAML\XMLSchema\Constants as C_XS;

/**
 * XPath helper functions for the XML library.
 *
 * @package simplesamlphp/xml-common
 */
class XPath
{
    /**
     * Get an instance of DOMXPath associated with a DOMNode
     *
     * - Reuses a cached DOMXPath per document.
     * - Registers core XML-related namespaces: 'xml' and 'xs'.
     * - Enriches the XPath with all prefixed xmlns declarations found on the
     *   current node and its ancestors (up to the document element), so
     *   custom prefixes declared anywhere up the tree can be used in queries.
     *
     * @param \DOMNode $node The associated node
     * @return \DOMXPath
     */
    public static function getXPath(DOMNode $node): DOMXPath
    {
        static $xpCache = null;

        if ($node instanceof DOMDocument) {
            $doc = $node;
        } else {
            $doc = $node->ownerDocument;
            Assert::notNull($doc);
        }

        if ($xpCache === null || !$xpCache->document->isSameNode($doc)) {
            $xpCache = new DOMXPath($doc);
        }

        $xpCache->registerNamespace('xml', C_XML::NS_XML);
        $xpCache->registerNamespace('xs', C_XS::NS_XS);

        // Enrich with ancestor-declared prefixes for this document context.
        self::registerAncestorNamespaces($xpCache, $node);

        return $xpCache;
    }


    /**
     * Walk from the given node up to the document element, registering all prefixed xmlns declarations.
     *
     * Safety:
     * - Only attributes in the XMLNS namespace (http://www.w3.org/2000/xmlns/).
     * - Skip default xmlns (localName === 'xmlns') because XPath requires prefixes.
     * - Skip empty URIs.
     * - Do not override core 'xml' and 'xs' prefixes (already bound).
     * - Nearest binding wins during this pass (prefixes are added once).
     *
     * @param \DOMXPath $xp
     * @param \DOMNode  $node
     */
    private static function registerAncestorNamespaces(DOMXPath $xp, DOMNode $node): void
    {
        // Avoid re-binding while walking upwards.
        $registered = [
            'xml' => true,
            'xs'  => true,
        ];

        // Start from the nearest element (or documentElement if a DOMDocument is passed).
        $current = $node instanceof DOMDocument
            ? $node->documentElement
            : ($node instanceof DOMElement ? $node : $node->parentNode);

        while ($current instanceof DOMElement) {
            if ($current->hasAttributes()) {
                foreach ($current->attributes as $attr) {
                    if ($attr->namespaceURI !== C_XML::NS_XMLNS) {
                        continue;
                    }
                    $prefix = $attr->localName; // e.g., 'slate' for xmlns:slate, 'xmlns' for default
                    $uri = (string) $attr->nodeValue;

                    if (
                        $prefix === null || $prefix === '' ||
                        $prefix === 'xmlns' || $uri === '' ||
                        isset($registered[$prefix])
                    ) {
                        continue;
                    }

                    $xp->registerNamespace($prefix, $uri);
                    $registered[$prefix] = true;
                }
            }

            $current = $current->parentNode instanceof DOMElement ? $current->parentNode : null;
        }
    }


    /**
     * Do an XPath query on an XML node.
     *
     * @param \DOMNode $node  The XML node.
     * @param string $query The query.
     * @param \DOMXPath $xpCache The DOMXPath object
     * @return array<\DOMNode> Array with matching DOM nodes.
     */
    public static function xpQuery(DOMNode $node, string $query, DOMXPath $xpCache): array
    {
        $ret = [];

        $results = $xpCache->query($query, $node);
        Assert::notFalse($results, 'Malformed XPath query or invalid contextNode provided.');

        for ($i = 0; $i < $results->length; $i++) {
            $ret[$i] = $results->item($i);
        }

        return $ret;
    }
}
