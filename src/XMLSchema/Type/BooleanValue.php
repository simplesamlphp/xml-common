<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Type;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Interface\AbstractAnySimpleType;

use function boolval;
use function in_array;

/**
 * @package simplesaml/xml-common
 */
class BooleanValue extends AbstractAnySimpleType
{
    /** @var string */
    public const SCHEMA_TYPE = 'boolean';


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
        // Note: value must already be sanitized before validating
        Assert::validBoolean($this->sanitizeValue($value), SchemaViolationException::class);
    }


    /**
     * @param boolean $value
     * @return static
     */
    public static function fromBoolean(bool $value): static
    {
        return new static(
            $value === true ? 'true' : 'false',
        );
    }


    /**
     * @return boolean $value
     */
    public function toBoolean(): bool
    {
        return boolval(
            in_array($this->getValue(), ['1', 'true']) ? '1' : '0',
        );
    }
}
