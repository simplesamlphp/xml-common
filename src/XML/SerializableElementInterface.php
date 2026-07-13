<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use Dom;

/**
 * interface class to be implemented by all the classes that represent a serializable XML element
 *
 * @package simplesamlphp/xml-common
 */
interface SerializableElementInterface extends ElementInterface
{
    /**
     * Output the class as an XML-formatted string
     */
    public function __toString(): string;


    /**
     * Test if an object, at the state it's in, would produce an empty XML-element
     */
    public function isEmptyElement(): bool;


    /**
     * Create a class from XML
     *
     * @param \Dom\Element $xml
     */
    public static function fromXML(Dom\Element $xml): static;


    /**
     * Create XML from this class
     *
     * @param \Dom\Element|null $parent
     */
    public function toXML(?Dom\Element $parent = null): Dom\Element;
}
