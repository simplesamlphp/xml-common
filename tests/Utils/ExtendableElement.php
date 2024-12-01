<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use DOMElement;
use SimpleSAML\XML\AbstractElement;
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

    /** @var \SimpleSAML\XML\XsNamespace|array<int, \SimpleSAML\XML\XsNamespace> */
    final public const XS_ANY_ELT_NAMESPACE = NS::ANY;

    /** @var array{array{string, string}} */
    final public const XS_ANY_ELT_EXCLUSIONS = [
        ['urn:custom:other', 'Chunk'],
    ];


    /**
     * Initialize element.
     *
     * @param \SimpleSAML\XML\SerializableElementInterface[] $elements
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
        return new static(self::getChildElementsFromXML($xml));
    }


    /**
     * Create XML from this class
     *
     * @param \DOMElement|null $parent
     * @return \DOMElement
     */
    public function toXML(?DOMElement $parent = null): DOMElement
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
