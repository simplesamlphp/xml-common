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
     * @var list{\SimpleSAML\XML\Attribute}
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
     * @return \SimpleSAML\XML\Attribute|null The value of the attribute, or null if the attribute does not exist.
     */
    public function getAttributeNS(string $namespaceURI, string $localName): ?Attribute
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
     * @return list{\SimpleSAML\XML\Attribute}
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
     * @return list{\SimpleSAML\XML\Attribute} $attributes
     */
    protected static function getAttributesNSFromXML(DOMElement $xml): array
    {
        $attributes = [];

        foreach ($xml->attributes as $a) {
            if ($a->namespaceURI !== null) {
                $attributes[] = new Attribute($a->namespaceURI, $a->prefix, $a->localName, $a->nodeValue);
            }
        }

        return $attributes;
    }


    /**
     * @param list{\SimpleSAML\XML\Attribute} $attributes
     * @throws \SimpleSAML\Assert\AssertionFailedException if $attributes contains anything other than Attribute objects
     */
    protected function setAttributesNS(array $attributes): void
    {
        Assert::allIsInstanceOf(
            $attributes,
            Attribute::class,
            'Arbitrary XML attributes can only be an instance of Attribute.',
        );
        $this->namespacedAttributes = $attributes;
    }



    /**
     * @return array|string
     */
    public function getAttributeNamespace(): array|string
    {
        Assert::true(
            defined('static::XS_ANY_ATTR_NAMESPACE'),
            self::getClassName(static::class)
            . '::XS_ANY_ATTR_NAMESPACE constant must be defined and set to the namespace for the xs:any element.',
            RuntimeException::class,
        );

        return static::NS_ANY_ATTR_NAMESPACE;
    }
}
