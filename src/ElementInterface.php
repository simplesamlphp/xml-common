<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMElement;

/**
 * interface class to be implemented by all the classes that represent an XML element
 *
 * @package simplesamlphp/xml-common
 */
interface ElementInterface
{
    /**
     * Get the value of an attribute from a given element.
     *
     * @param \DOMElement $xml The element where we should search for the attribute.
     * @param string      $name The name of the attribute.
     * @param string|null $default The default to return in case the attribute does not exist and it is optional.
     * @return string|null
     * @throws \SimpleSAML\Assert\AssertionFailedException if the attribute is missing from the element
     */
    public static function getAttribute(DOMElement $xml, string $name, ?string $default = ''): ?string;


    /**
     * @param \DOMElement $xml The element where we should search for the attribute.
     * @param string      $name The name of the attribute.
     * @param string|null $default The default to return in case the attribute does not exist and it is optional.
     * @return bool|null
     * @throws \SimpleSAML\Assert\AssertionFailedException if the attribute is not a boolean
     */
    public static function getBooleanAttribute(DOMElement $xml, string $name, ?string $default = ''): ?bool;


    /**
     * Get the integer value of an attribute from a given element.
     *
     * @param \DOMElement $xml The element where we should search for the attribute.
     * @param string      $name The name of the attribute.
     * @param string|null $default The default to return in case the attribute does not exist and it is optional.
     *
     * @return int|null
     * @throws \SimpleSAML\Assert\AssertionFailedException if the attribute is not an integer
     */
    public static function getIntegerAttribute(DOMElement $xml, string $name, ?string $default = ''): ?int;
}
