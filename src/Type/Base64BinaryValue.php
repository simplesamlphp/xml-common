<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Type;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Exception\SchemaViolationException;

use function str_replace;

/**
 * @package simplesaml/xml-common
 */
class Base64BinaryValue extends AbstractValueType
{
    /**
     * Sanitize the value.
     *
     * Note:  There are no processing rules for xs:base64 regarding whitespace. General consensus is to strip them
     *
     * @param string $value  The unsanitized value
     * @return string
     */
    protected function sanitizeValue(string $value): string
    {
        return str_replace(["\f", "\r", "\n", "\t", "\v", ' '], '', $value);
    }


    /**
     * Validate the value.
     *
     * @param string $value
     * @throws \SimpleSAML\XML\Exception\SchemaViolationException on failure
     * @return void
     */
    protected function validateValue(string $value): void
    {
        // Note: value must already be sanitized before validating
        Assert::validBase64Binary($this->sanitizeValue($value), SchemaViolationException::class);
    }
}
