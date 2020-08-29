<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMElement;
use Serializable;

/**
 * interface class to be implemented by all the classes that represent an XML element
 *
 * @author Tim van Dijen, <tvdijen@gmail.com>
 * @package simplesamlphp/xml-common
 */
interface XMLElementInterface
{
    /**
     * Output the class as an XML-formatted string
     *
     * @return string
     */
    public function __toString(): string;


    /**
     * Test if an object, at the state it's in, would produce an empty XML-element
     *
     * @return bool
     */
    public function isEmptyElement();


    /**
     * Create a class from XML
     *
     * @param \DOMElement $xml
     * @return self
     */
    public static function fromXML(DOMElement $xml): object;


    /**
     * Create XML from this class
     *
     * @param \DOMElement|null $parent
     * @return \DOMElement
     */
    public function toXML(DOMElement $parent = null): DOMElement;
}
