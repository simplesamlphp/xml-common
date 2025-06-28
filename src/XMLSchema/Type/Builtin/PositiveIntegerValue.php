<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Type\Builtin;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;

/**
 * @package simplesaml/xml-common
 */
class PositiveIntegerValue extends NonNegativeIntegerValue
{
    /** @var string */
    public const SCHEMA_TYPE = 'positiveInteger';


    /**
     * Validate the value.
     *
     * @param string $value
     * @throws \SimpleSAML\XMLSchema\Exception\SchemaViolationException on failure
     * @return void
     */
    protected function validateValue(string $value): void
    {
        Assert::validPositiveInteger($this->sanitizeValue($value), SchemaViolationException::class);
    }
}
