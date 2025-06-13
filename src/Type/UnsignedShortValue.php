<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Type;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Exception\SchemaViolationException;

/**
 * @package simplesaml/xml-common
 */
class UnsignedShortValue extends NonNegativeIntegerValue
{
    /** @var string */
    public const SCHEMA_TYPE = 'unsignedShort';


    /**
     * Validate the value.
     *
     * @param string $value
     * @throws \SimpleSAML\XML\Exception\SchemaViolationException on failure
     * @return void
     */
    protected function validateValue(string $value): void
    {
        Assert::validUnsignedShort($this->sanitizeValue($value), SchemaViolationException::class);
    }
}
