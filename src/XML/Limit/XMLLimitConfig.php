<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Limit;

final class XMLLimitConfig
{
    // Hard defaults that must be enforced at all time
    // while making the O(KN²) / O(N²) / O(D³W²) paths impractical.
    public const int HARD_MAX_NODE         = 100000;   // total Element + Attribute + Text + ...

    public const int HARD_MAX_ATTRIBUTE    = 200;      // per element

    public const int HARD_MAX_NS_DECL      = 64;       // xmlns* attributes per element

    public const int HARD_MAX_DEPTH        = 128;      // nesting depth

    public const int HARD_MAX_NS_IN_SCOPE  = 128;      // distinct prefixes/namespaces visible at any point

    // Conservative defaults that still allow every common legitimate XML-document
    public const int DEFAULT_MAX_NODE         = 25000;  // total Element + Attribute + Text + ...

    public const int DEFAULT_MAX_ATTRIBUTE    = 100;    // per element

    public const int DEFAULT_MAX_NS_DECL      = 40;     // xmlns* attributes per element

    public const int DEFAULT_MAX_DEPTH        = 64;     // nesting depth

    public const int DEFAULT_MAX_NS_IN_SCOPE  = 64;     // distinct prefixes/namespaces visible at any point

    // Even more conservative defaults that should still allow most XML-documents
    // Think of relatively small XML-structures like SAML 2.0 responses or metadata
    public const int STRICT_MAX_NODE         = 5000;    // total Element + Attribute + Text + ...

    public const int STRICT_MAX_ATTRIBUTE    = 60;      // per element

    public const int STRICT_MAX_NS_DECL      = 20;      // xmlns* attributes per element

    public const int STRICT_MAX_DEPTH        = 32;      // nesting depth

    public const int STRICT_MAX_NS_IN_SCOPE  = 40;      // distinct prefixes/namespaces visible at any point


    /**
     * Initialize an XMLLimit class
     */
    public function __construct(
        public int $maxNode = self::DEFAULT_MAX_NODE,
        public int $maxAttribute = self::DEFAULT_MAX_ATTRIBUTE,
        public int $maxNsDecl = self::DEFAULT_MAX_NS_DECL,
        public int $maxDepth = self::DEFAULT_MAX_DEPTH,
        public int $maxNsInScope = self::DEFAULT_MAX_NS_IN_SCOPE,
    ) {
    }


    /**
     * Return the strict limits
     */
    public static function getStrictDefaults(): static
    {
        return new static(
            self::STRICT_MAX_NODE,
            self::STRICT_MAX_ATTRIBUTE,
            self::STRICT_MAX_NS_DECL,
            self::STRICT_MAX_DEPTH,
            self::STRICT_MAX_NS_IN_SCOPE,
        );
    }


    /**
     * Return the default limits
     */
    public static function getDefaultXMLDefaults(): static
    {
        return new static(
            self::DEFAULT_MAX_NODE,
            self::DEFAULT_MAX_ATTRIBUTE,
            self::DEFAULT_MAX_NS_DECL,
            self::DEFAULT_MAX_DEPTH,
            self::DEFAULT_MAX_NS_IN_SCOPE,
        );
    }


    /**
     * Return the hard limits
     */
    public static function getHardDefaults(): static
    {
        return new static(
            self::HARD_MAX_NODE,
            self::HARD_MAX_ATTRIBUTE,
            self::HARD_MAX_NS_DECL,
            self::HARD_MAX_DEPTH,
            self::HARD_MAX_NS_IN_SCOPE,
        );
    }
}
