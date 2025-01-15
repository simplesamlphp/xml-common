<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Type;

use Stringable;

/**
 * interface class to be implemented by all the classes that represent a value type
 *
 * @package simplesamlphp/xml-common
 */
interface ValueTypeInterface extends Stringable
{
    /**
     * @return string
     */
    public function getValue(): string;


    /**
     * @return string
     */
    public function getRawValue(): string;


    /**
     * @param string $value
     * @return static
     */
    public static function fromString(string $value): static;


    /**
     * Output the value as a string
     *
     * @return string
     */
    public function __toString(): string;
}
