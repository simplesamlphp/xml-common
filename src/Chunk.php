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
final class Chunk extends AbstractXMLElement
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
    protected $namespace;

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
        $this->setNamespace($xml->namespace);
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
    public function getNamespace(): ?string
    {
        return $this->namespace;
    }


    /**
     * Set the value of the namespaceURI-property
     *
     * @param string|null $namespace
     * @return void
     */
    protected function setNamespace(string $namespace = null): void
    {
        $this->namespace = $namespace;
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
     * Get the namespace for the element.
     *
     * @return string|null
     */
    public static function getNamespaceURI(): ?string
    {
        return null;
    }


    /**
     * Get the namespace-prefix for the element.
     *
     * @return string|null
     */
    public static function getNamespacePrefix(): string
    {
        return null;
    }
}
