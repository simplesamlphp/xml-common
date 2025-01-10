<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Type;

/**
 * interface class to be implemented by all the classes that represent a value type
 *
 * @package simplesamlphp/xml-common
 */
interface ValueTypeInterface
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
     * @return \SimpleSAML\XML\Type\ValueTypeInterface
     */
    public static function fromString(string $value): ValueTypeInterface;
}
