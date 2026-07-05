<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use Dom;
use SimpleSAML\XML\DOMDocumentFactory;

use function array_last;
use function get_object_vars;

/**
 * Trait grouping common functionality for elements implementing the SerializableElement element.
 *
 * @package simplesamlphp/xml-common
 */
trait SerializableElementTrait
{
    /**
     * Whether to format the string output of this element or not.
     *
     * Defaults to true. Override to disable output formatting.
     */
    protected bool $formatOutput = true;


    /**
     * Output the class as an XML-formatted string
     */
    public function __toString(): string
    {
        $xml = $this->toXML();
        $doc = $xml->ownerDocument;

        if (static::getNormalization() === true) {
            $normalized = DOMDocumentFactory::normalizeDocument($doc);
            $normalized->formatOutput = $this->formatOutput;
            return $normalized->saveXml($normalized->documentElement);
        }

        // Non-normalized path
        $doc->formatOutput = $this->formatOutput;
        $doc->normalizeDocument();
        return $doc->saveXml($doc->documentElement);
    }


    /**
     * Serialize this XML chunk.
     *
     * This method will be invoked by any calls to serialize().
     *
     * @return array{0: string} The serialized representation of this XML object.
     */
    public function __serialize(): array
    {
        $xml = $this->toXML();
        /** @var \Dom\XMLDocument $ownerDocument */
        $ownerDocument = $xml->ownerDocument;
        return [$ownerDocument->saveXml($xml)];
    }


    /**
     * Unserialize an XML object and load it..
     *
     * This method will be invoked by any calls to unserialize(), allowing us to restore any data that might not
     * be serializable in its original form (e.g.: DOM objects).
     *
     * @param array{0: string} $serialized The XML object that we want to restore.
     */
    public function __unserialize(array $serialized): void
    {
        $xml = static::fromXML(
            DOMDocumentFactory::fromString(array_last($serialized))->documentElement,
        );

        $vars = get_object_vars($xml);
        foreach ($vars as $k => $v) {
            $this->$k = $v;
        }
    }


    /**
     * Create XML from this class
     *
     * @param \Dom\Element|null $parent
     */
    abstract public function toXML(?Dom\Element $parent = null): Dom\Element;
}
