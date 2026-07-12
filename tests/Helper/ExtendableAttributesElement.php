<?php

declare(strict_types=1);

namespace SimpleSAML\Test\Helper;

use Dom;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\ExtendableAttributesTrait;
use SimpleSAML\XML\SchemaValidatableElementInterface;
use SimpleSAML\XML\SchemaValidatableElementTrait;
use SimpleSAML\XMLSchema\Exception\InvalidDOMElementException;
use SimpleSAML\XMLSchema\XML\Constants\NS;

/**
 * Empty shell class for testing ExtendableAttributesTrait.
 *
 * @package simplesaml/xml-security
 */
class ExtendableAttributesElement extends AbstractElement implements SchemaValidatableElementInterface
{
    use ExtendableAttributesTrait;
    use SchemaValidatableElementTrait;


    public const string NS = 'urn:x-simplesamlphp:namespace';

    public const string NS_PREFIX = 'ssp';

    public const string LOCALNAME = 'ExtendableAttributesElement';

    public const string SCHEMA = 'tests/resources/schemas/simplesamlphp.xsd';

    final public const string XS_ANY_ATTR_NAMESPACE = NS::ANY;

    /** @var array{array{string, string}} */
    final public const array XS_ANY_ATTR_EXCLUSIONS = [
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
     * @param \Dom\Element $xml
     * @return static
     */
    public static function fromXML(Dom\Element $xml): static
    {
        Assert::same($xml->localName, 'ExtendableAttributesElement', InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, 'urn:x-simplesamlphp:namespace', InvalidDOMElementException::class);

        return new static(self::getAttributesNSFromXML($xml));
    }


    /**
     * Create XML from this class
     *
     * @param \Dom\Element|null $parent
     * @return \Dom\Element
     */
    public function toXML(?Dom\Element $parent = null): Dom\Element
    {
        $e = $this->instantiateParentElement($parent);

        foreach ($this->getAttributesNS() as $attr) {
            $attr->toXML($e);
        }

        // @phpstan-ignore argument.type, return.type
        return DOMDocumentFactory::normalizeDocument($e->ownerDocument)->documentElement;
    }
}
