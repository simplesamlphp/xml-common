<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Type;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Exception\SchemaViolationException;

use function preg_replace;
use function trim;

/**
 * @package simplesaml/xml-common
 */
class DateTimeValue extends AbstractValueType
{
    /**
     * Sanitize the value.
     *
     * @param string $value  The unsanitized value
     * @return string
     */
    protected function sanitizeValue(string $value): string
    {
        return trim(preg_replace('/\s+/', ' ', $value));
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
        Assert::validDateTime($this->sanitizeValue($value), SchemaViolationException::class);
    }
}
