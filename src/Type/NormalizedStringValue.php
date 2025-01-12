<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Type;

use function preg_replace;

/**
 * @package simplesaml/xml-common
 */
class NormalizedStringValue extends StringValue
{
    /**
     * Sanitize the value.
     *
     * @param string $value  The unsanitized value
     * @return string
     */
    protected function sanitizeValue(string $value): string
    {
        return preg_replace('/\s/', ' ', $value);
    }
}
