<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use DOMElement;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\ExtendableElementTrait;
use SimpleSAML\XML\SerializableElementTrait;
use SimpleSAML\XML\XsNamespace as NS;

/**
 * Empty shell class for testing ExtendableElementTrait.
 *
 * @package simplesaml/xml-security
 */
class ExtendableElement extends AbstractElement
{
    use ExtendableElementTrait;
    use SerializableElementTrait;


    /** @var string */
    public const NS = 'urn:x-simplesamlphp:namespace';

    /** @var string */
    public const NS_PREFIX = 'ssp';

    /** @var string */
    public const LOCALNAME = 'ExtendableElement';

    /** @var \SimpleSAML\XML\XsNamespace|array */
    public const XS_ANY_ELT_NAMESPACE = NS::ANY;


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
     * @param \SimpleSAML\XML\ElementInterface[] $elements
     */
    final public function __construct(array $elements)
    {
        $this->setElements($elements);
    }


    /**
     * Create a class from XML
     *
     * @param \DOMElement $xml
     * @return static
     */
    public static function fromXML(DOMElement $xml): static
    {
        $children = [];
        foreach ($xml->childNodes as $node) {
            if ($node instanceof DOMElement) {
                $children[] = Chunk::fromXML($node);
            }
        }

        return new static($children);
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

        /** @psalm-var \SimpleSAML\XML\SerializableElementInterface $elt */
        foreach ($this->getElements() as $elt) {
            if (!$elt->isEmptyElement()) {
                $elt->toXML($e);
            }
        }

        return $e;
    }
}
