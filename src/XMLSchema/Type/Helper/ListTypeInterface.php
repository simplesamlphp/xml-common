<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Type\Helper;

/**
 * interface class to be implemented by all the classes that represent a list type
 *
 * @package simplesamlphp/xml-common
 */
interface ListTypeInterface extends ValueTypeInterface
{
    /**
     * Convert this list type to an array of individual items
     *
     * @return array<\SimpleSAML\XMLSchema\Type\Helper\ValueTypeInterface>
     */
    public function toArray(): array;
}
