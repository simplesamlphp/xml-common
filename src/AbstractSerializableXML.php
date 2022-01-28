<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMElement;
use SimpleSAML\XML\DOMDocumentFactory;
use Serializable;

use function get_object_vars;

/**
 * Abstract class for serialization of XML structures
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractSerializableXML implements XMLElementInterface, Serializable
{
    /**
     * Whether to format the string output of this element or not.
     *
     * Defaults to true. Override to disable output formatting.
     *
     * @var bool
     */
    protected bool $formatOutput = true;


    /**
     * Output the class as an XML-formatted string
     *
     * @return string
     */
    public function __toString(): string
    {
        $xml = $this->toXML();
        /** @psalm-var \DOMDocument $xml->ownerDocument */
        $xml->ownerDocument->formatOutput = $this->formatOutput;
        return $xml->ownerDocument->saveXML($xml);
    }


    /**
     * Serialize this XML chunk
     *
     * @return string The serialized chunk.
     */
    public function serialize(): string
    {
        $xml = $this->toXML();
        /** @psalm-var \DOMDocument $xml->ownerDocument */
        return $xml->ownerDocument->saveXML($xml);
    }


    /**
     * Un-serialize this XML chunk.
     *
     * @param string $serialized The serialized chunk.
     *
     * Type hint not possible due to upstream method signature
     */
    public function unserialize($serialized): void
    {
        $doc = DOMDocumentFactory::fromString($serialized);
        $obj = static::fromXML($doc->documentElement);

        // For this to work, the properties have to be protected
        foreach (get_object_vars($obj) as $property => $value) {
            $this->{$property} = $value;
        }
    }


    /**
     * Test if an object, at the state it's in, would produce an empty XML-element
     *
     * @return bool
     */
    abstract public function isEmptyElement(): bool;


    /**
     * Create a class from XML
     *
     * @param \DOMElement $xml
     * @return self
     */
    abstract public static function fromXML(DOMElement $xml): object;


    /**
     * Create XML from this class
     *
     * @param \DOMElement|null $parent
     * @return \DOMElement
     */
    abstract public function toXML(DOMElement $parent = null): DOMElement;
}
