<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Type;

use DateTimeImmutable;
use DateTimeInterface;
use Psr\Clock\ClockInterface;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Interface\AbstractAnySimpleType;

/**
 * @package simplesaml/xml-common
 */
class DateTimeValue extends AbstractAnySimpleType
{
    public const string SCHEMA_TYPE = 'dateTime';

    public const string DATETIME_FORMAT = 'Y-m-d\\TH:i:sP';


    /**
     * Sanitize the value.
     *
     * @param string $value  The unsanitized value
     */
    protected function sanitizeValue(string $value): string
    {
        return static::collapseWhitespace(static::normalizeWhitespace($value));
    }


    /**
     * Validate the value.
     *
     * @param string $value
     * @throws \SimpleSAML\XMLSchema\Exception\SchemaViolationException on failure
     */
    protected function validateValue(string $value): void
    {
        Assert::validDateTime($this->sanitizeValue($value), SchemaViolationException::class);
    }


    /**
     */
    public static function now(ClockInterface $clock): static
    {
        return static::fromDateTime($clock->now());
    }


    /**
     * @param \DateTimeInterface $value
     */
    public static function fromDateTime(DateTimeInterface $value): static
    {
        return new static($value->format(static::DATETIME_FORMAT));
    }


    /**
     */
    public function toDateTime(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->getValue());
    }
}
