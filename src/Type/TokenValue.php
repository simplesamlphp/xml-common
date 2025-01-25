<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Type;

/**
 * @package simplesaml/xml-common
 */
class TokenValue extends NormalizedStringValue
{
    /** @var string */
    public const SCHEMA_TYPE = 'xs:token';


    /**
     * Sanitize the value.
     *
     * @param string $value  The unsanitized value
     * @return string
     */
    protected function sanitizeValue(string $value): string
    {
        return static::collapseWhitespace(static::normalizeWhitespace($value));
    }
}
