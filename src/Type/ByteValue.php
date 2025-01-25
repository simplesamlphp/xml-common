<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Type;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Exception\SchemaViolationException;

/**
 * @package simplesaml/xml-common
 */
class ByteValue extends LongValue
{
    /** @var string */
    public const SCHEMA_TYPE = 'xs:byte';


    /**
     * Validate the value.
     *
     * @param string $value
     * @throws \SimpleSAML\XML\Exception\SchemaViolationException on failure
     * @return void
     */
    protected function validateValue(string $value): void
    {
        Assert::validByte($this->sanitizeValue($value), SchemaViolationException::class);
    }
}
