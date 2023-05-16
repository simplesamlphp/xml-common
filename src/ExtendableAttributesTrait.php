<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMElement;
use SimpleSAML\Assert\Assert;

/**
 * Trait for elements that can have arbitrary namespaced attributes.
 *
 * @package simplesamlphp/xml-common
 */
trait ExtendableAttributesTrait
{
    /**
     * Extra (namespace qualified) attributes.
     *
     * @var list{\SimpleSAML\XML\XMLAttribute}
     */
    protected array $namespacedAttributes = [];


    /**
     * Check if a namespace-qualified attribute exists.
     *
     * @param string $namespaceURI The namespace URI.
     * @param string $localName The local name.
     * @return bool true if the attribute exists, false if not.
     */
    public function hasAttributeNS(string $namespaceURI, string $localName): bool
    {
        foreach ($this->getAttributesNS() as $attr) {
            if ($attr->getNamespaceURI() === $namespaceURI && $attr->getAttrName() === $localName) {
                return true;
            }
        }
        return false;
    }


    /**
     * Get a namespace-qualified attribute.
     *
     * @param string $namespaceURI The namespace URI.
     * @param string $localName The local name.
     * @return \SimpleSAML\XML\XMLAttribute|null The value of the attribute, or null if the attribute does not exist.
     */
    public function getAttributeNS(string $namespaceURI, string $localName): ?XMLAttribute
    {
        foreach ($this->getAttributesNS() as $attr) {
            if ($attr->getNamespaceURI() === $namespaceURI && $attr->getAttrName() === $localName) {
                return $attr;
            }
        }
        return null;
    }


    /**
     * Get the namespaced attributes in this element.
     *
     * @return list{\SimpleSAML\XML\XMLAttribute}
     */
    public function getAttributesNS(): array
    {
        return $this->namespacedAttributes;
    }


    /**
     * Parse an XML document and get the namespaced attributes.
     *
     * @param \DOMElement $xml
     *
     * @return list{\SimpleSAML\XML\XMLAttribute} $attributes
     */
    protected static function getAttributesNSFromXML(DOMElement $xml): array
    {
        $attributes = [];

        foreach ($xml->attributes as $a) {
            if ($a->namespaceURI !== null) {
                $attributes[] = new XMLAttribute($a->namespaceURI, $a->prefix, $a->localName, $a->nodeValue);
            }
        }

        return $attributes;
    }


    /**
     * @param list{\SimpleSAML\XML\XMLAttribute} $attributes
     * @throws \SimpleSAML\Assert\AssertionFailedException if $attributes contains anything other than XMLAttribute objects
     */
    protected function setAttributesNS(array $attributes): void
    {
        Assert::allIsInstanceOf(
            $attributes,
            XMLAttribute::class,
            'Arbitrary XML attributes can only be an instance of XMLAttribute.',
        );
        $this->namespacedAttributes = $attributes;
    }
}
