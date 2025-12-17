<?php

declare(strict_types=1);

namespace SimpleSAML\Test\Helper;

use DOMElement;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\ExtendableElementTrait;
use SimpleSAML\XML\SchemaValidatableElementInterface;
use SimpleSAML\XML\SchemaValidatableElementTrait;
use SimpleSAML\XML\SerializableElementTrait;
use SimpleSAML\XMLSchema\XML\Constants\NS;

/**
 * Empty shell class for testing ExtendableElementTrait.
 *
 * @package simplesaml/xml-security
 */
class ExtendableElement extends AbstractElement implements SchemaValidatableElementInterface
{
    use ExtendableElementTrait;
    use SchemaValidatableElementTrait;
    use SerializableElementTrait;


    public const string NS = 'urn:x-simplesamlphp:namespace';

    public const string NS_PREFIX = 'ssp';

    public const string LOCALNAME = 'ExtendableElement';

    public const string SCHEMA = 'tests/resources/schemas/simplesamlphp.xsd';

    final public const string XS_ANY_ELT_NAMESPACE = NS::ANY;

    /** @var array{array{string, string}} */
    final public const array XS_ANY_ELT_EXCLUSIONS = [
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
        $e = $this->instantiateParentElement($parent);

        foreach ($this->getElements() as $elt) {
            if (!$elt->isEmptyElement()) {
                $elt->toXML($e);
            }
        }

        // @phpstan-ignore argument.type, return.type
        return DOMDocumentFactory::normalizeDocument($e->ownerDocument)->documentElement;
    }
}
