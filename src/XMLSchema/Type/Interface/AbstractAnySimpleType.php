<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Type\Interface;

use function is_string;
use function preg_replace;
use function strcmp;
use function trim;

/**
 * Abstract class to be implemented by all types
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractAnySimpleType implements ValueTypeInterface
{
    /** @var string */
    public const SCHEMA_TYPE = 'anySimpleType';

    /** @var string */
    public const SCHEMA_NAMESPACEURI = 'http://www.w3.org/2001/XMLSchema';

    /** @var string */
    public const SCHEMA_NAMESPACE_PREFIX = 'xs';


    /**
     * Set the value for this type.
     *
     * @param string $value
     */
    final protected function __construct(
        protected string $value,
    ) {
        $this->validateValue($value);
    }


    /**
     * Get the value.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->sanitizeValue($this->getRawValue());
    }


    /**
     * Get the raw unsanitized value.
     *
     * @return string
     */
    public function getRawValue(): string
    {
        return $this->value;
    }


    /**
     * Sanitize the value.
     *
     * @param string $value  The value
     * @throws \Exception on failure
     * @return string
     */
    protected function sanitizeValue(string $value): string
    {
        /**
         * Perform no sanitation by default.
         * Override this method on the implementing class to perform content sanitation.
         */
        return $value;
    }


    /**
     * Validate the value.
     *
     * @param string $value  The value
     * @throws \Exception on failure
     * @return void
     */
    protected function validateValue(/** @scrutinizer-ignore */string $value): void
    {
        /**
         * Perform no validation by default.
         * Override this method on the implementing class to perform validation.
         */
    }


    /**
     * Normalize whitespace in the value
     *
     * @return string
     */
    protected static function normalizeWhitespace(string $value): string
    {
        return preg_replace('/\s/', ' ', $value);
    }


    /**
     * Collapse whitespace
     *
     * @return string
     */
    protected static function collapseWhitespace(string $value): string
    {
        return trim(preg_replace('/\s+/', ' ', $value));
    }


    /**
     * @param string $value
     * @return static
     */
    public static function fromString(string $value): static
    {
        return new static($value);
    }


    /**
     * Output the value as a string
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }


    /**
     * Compare the value to another one
     *
     * @param \SimpleSAML\XMLSchema\Type\Interface\ValueTypeInterface|string $other
     * @return bool
     */
    public function equals(ValueTypeInterface|string $other): bool
    {
        if (is_string($other)) {
            $other = static::fromString($other);
        }

        return strcmp($this->getValue(), $other->getValue()) === 0;
    }
}
