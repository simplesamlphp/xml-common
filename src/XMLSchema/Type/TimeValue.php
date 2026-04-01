<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Type;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Interface\AbstractAnySimpleType;

use function rtrim;
use function strlen;
use function substr;

/**
 * @package simplesaml/xml-common
 */
class TimeValue extends AbstractAnySimpleType
{
    public const string SCHEMA_TYPE = 'time';


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
            $timezone = strrpos($normalized, '+') ?? strrpos($normalized, '-') ?? strrpos($normalized, 'Z');
            if ($timezone !== false) {
                $subseconds = substr($normalized, $decimal + 1, strlen($normalized) - $timezone);
            } else {
                $subseconds = substr($normalized, $decimal + 1);
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
        Assert::validTime($this->sanitizeValue($value), SchemaViolationException::class);
    }
}
