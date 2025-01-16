<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMElement;
use SimpleSAML\XML\Type\{StringValue, ValueTypeInterface};

/**
 * interface class to be implemented by all the classes that represent an XML element
 *
 * @package simplesamlphp/xml-common
 */
interface ElementInterface
{
    /**
     * Get the XML qualified name (prefix:name) of the element represented by this class.
     *
     * @return string
     */
    public function getQualifiedName(): string;


    /**
     * Get the value of an attribute from a given element.
     *
     * @param \DOMElement $xml The element where we should search for the attribute.
     * @param string      $name The name of the attribute.
     * @param string      $type The type of the attribute value
     * @return \SimpleSAML\XML\Type\ValueTypeInterface
     *
     * @throws \SimpleSAML\XML\Exception\MissingAttributeException if the attribute is missing from the element
     */
    public static function getAttribute(
        DOMElement $xml,
        string $name,
        string $type = StringValue::class,
    ): ValueTypeInterface;


    /**
     * Get the value of an attribute from a given element.
     *
     * @param \DOMElement $xml The element where we should search for the attribute.
     * @param string      $name The name of the attribute.
     * @param string      $type The type of the attribute value
     * @param \SimpleSAML\XML\Type\ValueTypeInterface|null $default
     *   The default to return in case the attribute does not exist and it is optional.
     * @return \SimpleSAML\XML\Type\ValueTypeInterface|null
     */
    public static function getOptionalAttribute(
        DOMElement $xml,
        string $name,
        string $type = StringValue::class,
        ?ValueTypeInterface $default = null,
    ): ?ValueTypeInterface;
}
