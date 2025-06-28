<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use DOMElement;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\{SchemaValidatableElementInterface, SchemaValidatableElementTrait};
use SimpleSAML\XMLSchema\Exception\{InvalidDOMElementException, SchemaViolationException, TooManyElementsException};
use SimpleSAML\XMLSchema\Type\Builtin\{BooleanValue, IDValue, NCNameValue, QNameValue, StringValue};
use SimpleSAML\XMLSchema\Type\{BlockSetValue, DerivationSetValue, FormChoiceValue, MaxOccursValue, MinOccursValue};

/**
 * Class representing the topLevelElement-type.
 *
 * @package simplesamlphp/xml-common
 */
final class TopLevelElement extends AbstractTopLevelElement implements SchemaValidatableElementInterface
{
    use SchemaValidatableElementTrait;

    /** @var string */
    public const LOCALNAME = 'element';


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
        $ref = self::getOptionalAttribute($xml, 'ref', QNameValue::class, null);
        Assert::null($ref, SchemaViolationException::class);

        $form = self::getOptionalAttribute($xml, 'form', FormChoiceValue::class, null);
        Assert::null($form, SchemaViolationException::class);

        $minCount = self::getOptionalAttribute($xml, 'minCount', MinOccursValue::class, null);
        Assert::null($minCount, SchemaViolationException::class);

        $maxCount = self::getOptionalAttribute($xml, 'maxCount', MaxOccursValue::class, null);
        Assert::null($maxCount, SchemaViolationException::class);

        // The annotation
        $annotation = Annotation::getChildrenOfClass($xml);
        Assert::maxCount($annotation, 1, TooManyElementsException::class);

        // The local type
        $localSimpleType = LocalSimpleType::getChildrenOfClass($xml);
        Assert::maxCount($localSimpleType, 1, TooManyElementsException::class);

        $localComplexType = LocalComplexType::getChildrenOfClass($xml);
        Assert::maxCount($localComplexType, 1, TooManyElementsException::class);

        $localType = array_merge($localSimpleType, $localComplexType);
        Assert::maxCount($localType, 1, TooManyElementsException::class);

        // The identity constraint
        $key = Key::getChildrenOfClass($xml);
        $keyref = Keyref::getChildrenOfClass($xml);
        $unique = Unique::getChildrenOfClass($xml);
        $identityConstraint = array_merge($key, $keyref, $unique);

        return new static(
            self::getAttribute($xml, 'name', NCNameValue::class),
            array_pop($localType),
            $identityConstraint,
            self::getOptionalAttribute($xml, 'type', QNameValue::class, null),
            self::getOptionalAttribute($xml, 'substitutionGroup', QNameValue::class, null),
            self::getOptionalAttribute($xml, 'default', StringValue::class, null),
            self::getOptionalAttribute($xml, 'fixed', StringValue::class, null),
            self::getOptionalAttribute($xml, 'nillable', BooleanValue::class, null),
            self::getOptionalAttribute($xml, 'abstract', BooleanValue::class, null),
            self::getOptionalAttribute($xml, 'final', DerivationSetValue::class, null),
            self::getOptionalAttribute($xml, 'block', BlockSetValue::class, null),
            array_pop($annotation),
            self::getOptionalAttribute($xml, 'id', IDValue::class, null),
            self::getAttributesNSFromXML($xml),
        );
    }
}
