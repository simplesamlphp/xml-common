<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Type;

use DateTimeImmutable;
use DateTimeInterface;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Interface\AbstractAnySimpleType;

/**
 * @package simplesaml/xml-common
 */
class DateTimeValue extends AbstractAnySimpleType
{
    /** @var string */
    public const SCHEMA_TYPE = 'dateTime';

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
     * @throws \SimpleSAML\XMLSchema\Exception\SchemaViolationException on failure
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


    /**
     * @return \DateTimeImmutable
     */
    public function toDateTime(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->getValue());
    }
}
