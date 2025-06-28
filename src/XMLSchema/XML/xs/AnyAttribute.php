<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use DOMElement;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\{SchemaValidatableElementInterface, SchemaValidatableElementTrait};
use SimpleSAML\XMLSchema\Exception\{InvalidDOMElementException, TooManyElementsException};
use SimpleSAML\XMLSchema\Type\Builtin\IDValue;
use SimpleSAML\XMLSchema\Type\{NamespaceListValue, ProcessContentsValue};

use function array_pop;

/**
 * Class representing the anyAttribute element
 *
 * @package simplesamlphp/xml-common
 */
final class AnyAttribute extends AbstractWildcard implements SchemaValidatableElementInterface
{
    use SchemaValidatableElementTrait;

    /** @var string */
    public const LOCALNAME = 'anyAttribute';


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

        $annotation = Annotation::getChildrenOfClass($xml);
        Assert::maxCount($annotation, 1, TooManyElementsException::class);

        return new static(
            self::getOptionalAttribute($xml, 'namespace', NamespaceListValue::class, null),
            self::getOptionalAttribute($xml, 'processContents', ProcessContentsValue::class, null),
            array_pop($annotation),
            self::getOptionalAttribute($xml, 'id', IDValue::class, null),
            self::getAttributesNSFromXML($xml),
        );
    }
}
