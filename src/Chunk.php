<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMElement;
use SimpleSAML\XML\Utils;
use SimpleSAML\Assert\Assert;

/**
 * Serializable class used to hold an XML element.
 *
 * @package simplesamlphp/xml-common
 */
final class Chunk extends AbstractSerializableXML
{
    /**
     * The localName of the element.
     *
     * @var string
     */
    protected $localName;

    /**
     * The namespaceURI of this element.
     *
     * @var string|null
     */
    protected $namespaceURI;

    /**
     * The prefix of this element.
     *
     * @var string|null
     */
    protected $prefix;

    /**
     * The \DOMElement we contain.
     *
     * @var \DOMElement
     */
    protected $xml;


    /**
     * Create a XMLChunk from a copy of the given \DOMElement.
     *
     * @param \DOMElement $xml The element we should copy.
     */
    public function __construct(DOMElement $xml)
    {
        $this->setLocalName($xml->localName);
        $this->setNamespaceURI($xml->namespaceURI);
        $this->setPrefix($xml->prefix);

        $this->xml = Utils::copyElement($xml);
    }


    /**
     * Get this \DOMElement.
     *
     * @return \DOMElement This element.
     */
    public function getXML(): DOMElement
    {
        return $this->xml;
    }


    /**
     * Collect the value of the localName-property
     *
     * @return string
     */
    public function getLocalName(): string
    {
        return $this->localName;
    }


    /**
     * Set the value of the localName-property
     *
     * @param string $localName
     * @return void
     * @throws \SimpleSAML\Assert\AssertionFailedException if $localName is an empty string
     */
    public function setLocalName(string $localName): void
    {
        Assert::notEmpty($localName, 'A DOMElement cannot have an empty name.');
        $this->localName = $localName;
    }


    /**
     * Collect the value of the namespaceURI-property
     *
     * @return string|null
     */
    public function getNamespaceURI(): ?string
    {
        return $this->namespaceURI;
    }


    /**
     * Set the value of the namespaceURI-property
     *
     * @param string|null $namespaceURI
     * @return void
     */
    protected function setNamespaceURI(string $namespaceURI = null): void
    {
        $this->namespaceURI = $namespaceURI;
    }


    /**
     * Collect the value of the prefix-property
     *
     * @return string|null
     */
    public function getPrefix(): ?string
    {
        return $this->prefix;
    }


    /**
     * Set the value of the prefix-property
     *
     * @param string|null $prefix
     * @return void
     */
    protected function setPrefix(string $prefix = null): void
    {
        $this->prefix = $prefix;
    }


    /**
     * Get the XML qualified name (prefix:name) of the element represented by this class.
     *
     * @return string
     */
    public function getQualifiedName(): string
    {
        return $this->getPrefix() . ':' . $this->getLocalName();
    }


    /**
     * @param \DOMElement $xml
     * @return self
     */
    public static function fromXML(DOMElement $xml): object
    {
        return new self($xml);
    }


    /**
     * Append this XML element to a different XML element.
     *
     * @param  \DOMElement|null $parent The element we should append this element to.
     * @return \DOMElement The new element.
     */
    public function toXML(DOMElement $parent = null): DOMElement
    {
        return Utils::copyElement($this->xml, $parent);
    }
}
