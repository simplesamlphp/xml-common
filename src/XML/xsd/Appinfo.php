<?php

declare(strict_types=1);

namespace SimpleSAML\XML\xsd;

use DOMElement;
use DOMNodeList;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\ExtendableAttributesTrait;
use SimpleSAML\XML\XsNamespace as NS;

/**
 * Class representing the appinfo element
 *
 * @package simplesamlphp/xml-common
 */
final class Appinfo extends AbstractXsdElement
{
    use ExtendableAttributesTrait;

    /** @var string */
    public const LOCALNAME = 'appinfo';

    /** The namespace-attribute for the xs:anyAttribute element */
    public const XS_ANY_ATTR_NAMESPACE = NS::OTHER;


    /**
     * Appinfo constructor
     *
     * @param \DOMNodeOist $content
     * @param string|null $source
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    final public function __construct(
        protected DOMNodeList $content,
        protected ?string $source = null,
        array $namespacedAttributes = [],
    ) {
        Assert::nullOrValidURI($source);
        $this->setAttributesNS($namespacedAttributes);
    }


    /**
     * Get the content property.
     *
     * @return \DOMNodeList
     */
    public function getContent(): DOMNodeList
    {
        return $this->content;
    }


    /**
     * Get the source property.
     *
     * @return string|null
     */
    public function getSource(): ?string
    {
        return $this->source;
    }


    /**
     * Test if an object, at the state it's in, would produce an empty XML-element
     *
     * @return bool
     */
    public function isEmptyElement(): bool
    {
        return empty($this->getContent())
            && empty($this->getSource())
            && empty($this->getAttributesNS());
    }


    /**
     * Create an instance of this object from its XML representation.
     *
     * @param \DOMElement $xml
     * @return static
     *
     * @throws \SimpleSAML\XML\Exception\InvalidDOMElementException
     *   if the qualified name of the supplied element is wrong
     */
    public static function fromXML(DOMElement $xml): static
    {
        Assert::same($xml->localName, static::getLocalName(), InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, static::NS, InvalidDOMElementException::class);

        return new static(
            $xml->childNodes,
            self::getOptionalAttribute($xml, 'source', null),
            self::getAttributesNSFromXML($xml),
        );
    }


    /**
     * Add this Appinfo to an XML element.
     *
     * @param \DOMElement $parent The element we should append this Appinfo to.
     * @return \DOMElement
     */
    public function toXML(DOMElement $parent = null): DOMElement
    {
        $e = parent::instantiateParentElement($parent);

        if ($this->getSource() !== null) {
            $e->setAttribute('source', $this->getSource());
        }

        foreach ($this->getAttributesNS() as $attr) {
            $attr->toXML($e);
        }

        foreach ($this->getContent() as $i) {
            $e->appendChild($e->ownerDocument->importNode($i, true));
        }

        return $e;
    }
}
