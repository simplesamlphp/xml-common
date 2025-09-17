<?php

declare(strict_types=1);

namespace SimpleSAML\Test\Helper;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\ExtendableAttributesTrait;
use SimpleSAML\XML\SchemaValidatableElementInterface;
use SimpleSAML\XML\SchemaValidatableElementTrait;
use SimpleSAML\XMLSchema\Exception\InvalidDOMElementException;
use SimpleSAML\XMLSchema\XML\Enumeration\NamespaceEnum;

/**
 * Empty shell class for testing ExtendableAttributesTrait.
 *
 * @package simplesaml/xml-security
 */
class ExtendableAttributesElement extends AbstractElement implements SchemaValidatableElementInterface
{
    use ExtendableAttributesTrait;
    use SchemaValidatableElementTrait;


    /** @var string */
    public const NS = 'urn:x-simplesamlphp:namespace';

    /** @var string */
    public const NS_PREFIX = 'ssp';

    /** @var string */
    public const LOCALNAME = 'ExtendableAttributesElement';

    /** @var string */
    public const SCHEMA = 'tests/resources/schemas/simplesamlphp.xsd';

    /**
     * @var (
     *   \SimpleSAML\XMLSchema\XML\Enumeration\NamespaceEnum|
     *   array<int, \SimpleSAML\XMLSchema\XML\Enumeration\NamespaceEnum|string>
     * )
     */
    final public const XS_ANY_ATTR_NAMESPACE = NamespaceEnum::Any;

    /** @var array{array{string, string}} */
    final public const XS_ANY_ATTR_EXCLUSIONS = [
        ['urn:x-simplesamlphp:namespace', 'attr3'],
    ];


    /**
     * Initialize element.
     *
     * @param \SimpleSAML\XML\Attribute[] $attributes
     */
    final public function __construct(array $attributes)
    {
        $this->setAttributesNS($attributes);
    }


    /**
     * Create a class from XML
     *
     * @param \DOMElement $xml
     * @return static
     */
    public static function fromXML(DOMElement $xml): static
    {
        Assert::same($xml->localName, 'ExtendableAttributesElement', InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, 'urn:x-simplesamlphp:namespace', InvalidDOMElementException::class);

        return new static(self::getAttributesNSFromXML($xml));
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

        foreach ($this->getAttributesNS() as $attr) {
            $attr->toXML($e);
        }

        return $e;
    }
}
