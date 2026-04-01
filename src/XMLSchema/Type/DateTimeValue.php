<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Type;

use DateTimeImmutable;
use DateTimeInterface;
use Psr\Clock\ClockInterface;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Interface\AbstractAnySimpleType;

use function rtrim;
use function strlen;
use function substr;

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
        $normalized = static::collapseWhitespace(static::normalizeWhitespace($value));
        // Trim any trailing zero's from the sub-seconds
        $decimal = strrpos($normalized, '.');
        if ($decimal !== false) {
            @list($dateValue, $timeValue) = explode('T', $normalized);
            Assert::notNull($dateValue);
            Assert::notNull($timeValue);

            $timezone = strrpos($timeValue, '+') ?: strrpos($timeValue, '-') ?: strrpos($timeValue, 'Z');
            if ($timezone !== false) {
                $subseconds = substr($timeValue, $decimal + $timezone, strlen($timeValue) - $timezone);
            } else {
                $subseconds = substr($timeValue, $decimal + 1);
            }

            $subseconds = rtrim($subseconds, '0');
            if ($subseconds === '') {
                return substr($normalized, 0, $decimal);
            }
            return substr($normalized, 0, $decimal + 1)
              . $subseconds
              . (($timezone === false) ? '' : substr($normalized, $timezone));
        }

        return $normalized;
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
