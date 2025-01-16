<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Type;

use function preg_replace;
use function trim;

/**
 * @package simplesaml/xml-common
 */
class TokenValue extends NormalizedStringValue
{
    /**
     * Sanitize the value.
     *
     * @param string $value  The unsanitized value
     * @return string
     */
    protected function sanitizeValue(string $value): string
    {
        return trim(preg_replace('/\s+/', ' ', parent::sanitizeValue($value)));
    }
}
