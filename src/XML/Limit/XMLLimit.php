<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Limit;

use Dom;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\Exception\StructuralLimitException;
use WeakMap;

use function count;
use function max;
use function sprintf;
use function str_starts_with;

final class XMLLimit
{
    private static ?XMLLimitConfig $config = null;

    /** @var \WeakMap<\Dom\Element, true> */
    private static ?\WeakMap $checked = null;


    public static function setDefault(?XMLLimitConfig $config): void
    {
        self::$config = $config;
    }


    public static function getDefault(): XMLLimitConfig
    {
        return self::$config ?? XMLLimitConfig::getHardDefaults();
    }


    public static function assertSafeXML(
        Dom\Element $root,
        ?XMLLimitConfig $config = null,
    ): void {
        self::$checked ??= new WeakMap();

        $doc = $root->ownerDocument;
        if (isset(self::$checked[$doc])) {
            return; // already validated this document
        }

        $config ??= self::getDefault();

        $nodeCount = 0;
        $maxObservedDepth = 0;
        $maxObservedNsInScope = 0;
        $maxNodes = $config->maxNode;
        $maxAttributes = $config->maxAttribute;
        $maxNsDecls = $config->maxNsDecl;
        $maxDepth = $config->maxDepth;
        $maxNsInScope = $config->maxNsInScope;

        $walker = function (
            Dom\Node $node,
            int $depth,
            array $inScopeNs,
        ) use (
            &$walker,
            &$nodeCount,
            &$maxObservedDepth,
            &$maxObservedNsInScope,
            $maxNodes,
            $maxAttributes,
            $maxNsDecls,
            $maxDepth,
            $maxNsInScope,
        ): void {
            if (++$nodeCount > $maxNodes) {
                throw new StructuralLimitException(
                    sprintf('XML structural limit exceeded: more than %d nodes', $maxNodes),
                );
            }
            if ($depth > $maxDepth) {
                throw new StructuralLimitException(
                    sprintf('XML structural limit exceeded: nesting depth > %d', $maxDepth),
                );
            }
            $maxObservedDepth = max($maxObservedDepth, $depth);

            if ($node->nodeType === XML_ELEMENT_NODE) {
                /** @var \Dom\Element $node */
                $attrCount = 0;
                $nsDeclCount = 0;
                $localNs = $inScopeNs;

                foreach ($node->attributes ?? [] as $attr) {
                    $attrCount++;
                    if ($attrCount > $maxAttributes) {
                        throw new StructuralLimitException(
                            sprintf('XML structural limit exceeded: element has > %d attributes', $maxAttributes),
                        );
                    }
                    // xmlns or xmlns:prefix
                    if ($attr->namespaceURI === C::NS_XMLNS || str_starts_with($attr->name, 'xmlns')) {
                        $nsDeclCount++;
                        if ($nsDeclCount > $maxNsDecls) {
                            throw new StructuralLimitException(sprintf(
                                'XML structural limit exceeded: > %d namespace declarations on one element',
                                $maxNsDecls,
                            ));
                        }
                        $prefix = $attr->prefix === 'xmlns' ? $attr->localName : '';
                        $localNs[$prefix] = $attr->value;
                    }
                }

                $nsInScope = count($localNs);
                if ($nsInScope > $maxNsInScope) {
                    throw new StructuralLimitException(sprintf(
                        'XML structural limit exceeded: > %d namespaces in scope',
                        $maxNsInScope,
                    ));
                }
                $maxObservedNsInScope = max($maxObservedNsInScope, $nsInScope);

                foreach ($node->childNodes as $child) {
                    $walker($child, $depth + 1, $localNs);
                }
            }
        };

        $walker($root, 1, []);

        self::$checked[$doc] = true;
    }
}
