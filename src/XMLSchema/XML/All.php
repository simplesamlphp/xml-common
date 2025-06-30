<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML;

use DOMElement;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\{SchemaValidatableElementInterface, SchemaValidatableElementTrait};
use SimpleSAML\XMLSchema\Exception\{
    InvalidDOMElementException,
    MissingElementException,
    SchemaViolationException,
    TooManyElementsException,
};
use SimpleSAML\XMLSchema\Type\{IDValue, NCNameValue, QNameValue};
use SimpleSAML\XMLSchema\Type\Schema\{MaxOccursValue, MinOccursValue};
use SimpleSAML\XMLSchema\XML\Interface\{ParticleInterface, TypeDefParticleInterface};

use function array_pop;

/**
 * Class representing the all-element.
 *
 * @package simplesamlphp/xml-common
 */
final class All extends AbstractAll implements
    ParticleInterface,
    SchemaValidatableElementInterface,
    TypeDefParticleInterface
{
    use SchemaValidatableElementTrait;

    /** @var string */
    public const LOCALNAME = 'all';


    /**
     * Create an instance of this object from its XML representation.
     *
     * @param \DOMElement $xml
     * @return static
     *
     * @throws \SimpleSAML\XMLSchema\Exception\InvalidDOMElementException
     *   if the qualified name of the supplied element is wrong
     */
    public static function fromXML(DOMElement $xml): static
    {
        Assert::same($xml->localName, static::getLocalName(), InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, static::NS, InvalidDOMElementException::class);

        // Prohibited attributes
        $name = self::getOptionalAttribute($xml, 'name', NCNameValue::class, null);
        Assert::null($name, SchemaViolationException::class);

        $ref = self::getOptionalAttribute($xml, 'ref', QNameValue::class, null);
        Assert::null($ref, SchemaViolationException::class);

        // The annotation
        $annotation = Annotation::getChildrenOfClass($xml);
        Assert::maxCount($annotation, 1, TooManyElementsException::class);

        // The content
        $narrowMaxMin = NarrowMaxMinElement::getChildrenOfClass($xml);

        return new static(
            self::getOptionalAttribute($xml, 'minCount', MinOccursValue::class, null),
            self::getOptionalAttribute($xml, 'maxCount', MaxOccursValue::class, null),
            $narrowMaxMin,
            annotation: array_pop($annotation),
            id: self::getOptionalAttribute($xml, 'id', IDValue::class, null),
            namespacedAttributes: self::getAttributesNSFromXML($xml),
        );
    }
}
