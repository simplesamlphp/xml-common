<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Type;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Exception\SchemaViolationException;

/**
 * @package simplesaml/xml-common
 */
class UnsignedByteValue extends IntegerValue
{
    /** @var string */
    public const SCHEMA_TYPE = 'xs:unsignedByte';


    /**
     * Validate the value.
     *
     * @param string $value
     * @throws \SimpleSAML\XML\Exception\SchemaViolationException on failure
     * @return void
     */
    protected function validateValue(string $value): void
    {
        Assert::validUnsignedByte($this->sanitizeValue($value), SchemaViolationException::class);
    }
}
