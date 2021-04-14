<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\AbstractXMLElement;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\Constants;
use SimpleSAML\XML\ExtendableElementTrait;
use SimpleSAML\XML\Exception\InvalidDOMElementException;

/**
 * Empty shell class for testing ExtendableElementTrait.
 *
 * @package simplesaml/xml-security
 */
class ExtendableElement extends AbstractXMLElement
{
    use ExtendableElementTrait;

    /** @var string */
    public const NS = 'urn:custom:ssp';

    /** @var string */
    public const NS_PREFIX = 'ssp';

    /** @var string|array */
    private $namespace = Constants::XS_ANY_NS_ANY;


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
     * @param \SimpleSAML\XML\XMLElementInterface[] $elements
     */
    public function __construct(array $elements)
    {
        $this->setElements($elements);
    }


    /**
     * Get the namespace-attribute for xs:any elements
     *
     * @return string|array
     */
    public function getNamespace()
    {
        return $this->namespace;
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

        return new self($children);
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
            if (!$elt->isEmptyElement()) {
                $elt->toXML($e);
            }
        }

        return $e;
    }
}
