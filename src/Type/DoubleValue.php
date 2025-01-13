<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Type;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Exception\SchemaViolationException;

/**
 * @package simplesaml/xml-common
 */
class DoubleValue extends AbstractValueType
{
    /**
     * Sanitize the value.
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
        Assert::validDouble($this->sanitizeValue($value), SchemaViolationException::class);
    }
}
