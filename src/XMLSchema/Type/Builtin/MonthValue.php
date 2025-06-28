<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Type\Builtin;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Helper\AbstractAnySimpleType;

/**
 * @package simplesaml/xml-common
 */
class MonthValue extends AbstractAnySimpleType
{
    /** @var string */
    public const SCHEMA_TYPE = 'gMonth';


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
        Assert::validMonth($this->sanitizeValue($value), SchemaViolationException::class);
    }
}
