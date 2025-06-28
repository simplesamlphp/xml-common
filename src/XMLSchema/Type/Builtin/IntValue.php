<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Type\Builtin;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;

/**
 * @package simplesaml/xml-common
 */
class IntValue extends LongValue
{
    /** @var string */
    public const SCHEMA_TYPE = 'int';


    /**
     * Validate the value.
     *
     * @param string $value
     * @throws \SimpleSAML\XMLSchema\Exception\SchemaViolationException on failure
     * @return void
     */
    protected function validateValue(string $value): void
    {
        Assert::validInt($this->sanitizeValue($value), SchemaViolationException::class);
    }
}
