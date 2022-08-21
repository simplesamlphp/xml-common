<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMElement;

/**
 * interface class to be implemented by all the classes that represent a serializable XML element
 *
 * @package simplesamlphp/xml-common
 */
interface SerializableElementInterface
{
    /**
     * Output the class as an XML-formatted string
     *
     * @return string
     */
    public function __toString(): string;


    /**
     * Create a class from XML
     *
     * @param \DOMElement $xml
     * @return self
     */
    public static function fromXML(DOMElement $xml): self;


    /**
     * Create XML from this class
     *
     * @param \DOMElement|null $parent
     * @return \DOMElement
     */
    public function toXML(DOMElement $parent = null): DOMElement;
}
