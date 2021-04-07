<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\AbstractXMLElement;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\ExtendableElementTrait;
use SimpleSAML\XML\Exception\InvalidDOMElementException;

/**
 * Empty shell class for testing ExtendableElementTrait.
 *
 * @package simplesaml/xml-security
 */
final class ExtendableElement extends AbstractXMLElement
{
    use ExtendableElementTrait {
        ExtendableElementTrait::isEmptyElement as parentIsEmptyElement;
    }

    /** @var string */
    public const NS = 'urn:custom:ssp';

    /** @var string */
    public const NS_PREFIX = 'ssp';


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
     */
    public function __construct()
    {
    }


    /**
     * Test if an object, at the state it's in, would produce an empty XML-element
     *
     * @return bool
     */
    public function isEmptyElement(): bool
    {
        return $this->parentIsEmptyElement();
    }


    /**
     * Create a class from XML
     *
     * @param \DOMElement $xml
     * @return self
     */
    public static function fromXML(DOMElement $xml): object
    {
        $children = [];
        foreach ($xml->childNodes as $node) {
            if ($node instanceof DOMElement) {
                $children[] = Chunk::fromXML($node);
            }
        }

        $instance = new self();
        $instance->setElements($children);
        return $instance;
    }


    /**
     * Create XML from this class
     *
     * @param \DOMElement|null $parent
     * @return \DOMElement
     */
    public function toXML(DOMElement $parent = null): DOMElement
    {
        $e = $this->instantiateParentElement();

        foreach ($this->getElements() as $elt) {
            $elt->toXML($e);
        }

        return $e;
    }
}
