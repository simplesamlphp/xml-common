<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\ExtendableAttributesTrait;
use SimpleSAML\XML\Exception\InvalidDOMElementException;

/**
 * Empty shell class for testing ExtendableAttributesTrait.
 *
 * @package simplesaml/xml-security
 */
class ExtendableAttributesElement extends AbstractElement
{
    use ExtendableAttributesTrait;


    /** @var string */
    public const NS = 'urn:x-simplesamlphp:namespace';

    /** @var string */
    public const NS_PREFIX = 'ssp';

    /** @var string */
    public const LOCALNAME = 'ExtendableAttributesElement';


    /**
     * Get the namespace for the element.
     *
     * @return string
     */
    public static function getNamespaceURI(): string
    {
        return static::NS;
    }


    /**
     * Get the namespace-prefix for the element.
     *
     * @return string
     */
    public static function getNamespacePrefix(): string
    {
        return static::NS_PREFIX;
    }


    /**
     * Initialize element.
     *
     * @param \DOMAttr[] $attributes
     */
    final public function __construct(array $attributes)
    {
        $this->setAttributesNS($attributes);
    }


    /**
     * Create a class from XML
     *
     * @param \DOMElement $xml
     * @return self
     */
    public static function fromXML(DOMElement $xml): self
    {
        Assert::same($xml->localName, 'ExtendableAttributesElement', InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, 'urn:x-simplesamlphp:namespace', InvalidDOMElementException::class);

        return new self(self::getAttributesNSFromXML($xml));
    }


    /**
     * Create XML from this class
     *
     * @param \DOMElement|null $parent
     * @return \DOMElement
     */
    public function toXML(DOMElement $parent = null): DOMElement
    {
        /** @psalm-var \DOMDocument $e->ownerDocument */
        $e = $this->instantiateParentElement($parent);

        foreach ($this->getAttributesNS() as $attr) {
            $e->setAttributeNS($attr['namespaceURI'], $attr['qualifiedName'], $attr['value']);
        }

        return $e;
    }
}
