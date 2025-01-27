<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Type;

use DateTimeInterface;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Exception\SchemaViolationException;

/**
 * @package simplesaml/xml-common
 */
class DateTimeValue extends AbstractValueType
{
    /** @var string */
    public const SCHEMA_TYPE = 'xs:dateTime';

    /** @var string */
    public const DATETIME_FORMAT = 'Y-m-d\\TH:i:sP';


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


    /**
     * @param \DateTimeInterface $value
     * @return static
     */
    public static function fromDateTime(DateTimeInterface $value): static
    {
        return new static($value->format(static::DATETIME_FORMAT));
    }
}
