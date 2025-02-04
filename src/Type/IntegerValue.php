<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Type;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Exception\{RuntimeException, SchemaViolationException};

use function bccomp;
use function intval;
use function strval;

/**
 * @package simplesaml/xml-common
 */
class IntegerValue extends DecimalValue
{
    /** @var string */
    public const SCHEMA_TYPE = 'integer';


    /**
     * Validate the value.
     *
     * @param string $value
     * @throws \SimpleSAML\XML\Exception\SchemaViolationException on failure
     * @return void
     */
    protected function validateValue(string $value): void
    {
        Assert::validInteger($this->sanitizeValue($value), SchemaViolationException::class);
    }


    /**
     * Convert from integer
     *
     * @param int $value
     * @return static
     */
    public static function fromInteger(int $value): static
    {
        return new static(strval($value));
    }


    /**
     * Convert to integer
     *
     * @return int
     */
    public function toInteger(): int
    {
        $value = $this->getValue();

        if (bccomp($value, strval(PHP_INT_MAX)) === 1) {
            throw new RuntimeException("Cannot convert to integer: out of bounds.");
        }

        return intval($value);
    }
}
