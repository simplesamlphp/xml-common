<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Type;

use function preg_replace;
use function trim;

/**
 * Abstract class to be implemented by all types
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractValueType implements ValueTypeInterface
{
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
}
