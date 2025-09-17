<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML;

use DOMElement;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\SchemaValidatableElementInterface;
use SimpleSAML\XML\SchemaValidatableElementTrait;
use SimpleSAML\XMLSchema\Exception\InvalidDOMElementException;
use SimpleSAML\XMLSchema\Exception\MissingElementException;
use SimpleSAML\XMLSchema\Exception\TooManyElementsException;
use SimpleSAML\XMLSchema\Type\IDValue;
use SimpleSAML\XMLSchema\Type\NCNameValue;
use SimpleSAML\XMLSchema\Type\Schema\SimpleDerivationSetValue;
use SimpleSAML\XMLSchema\XML\Interface\RedefinableInterface;

use function array_merge;
use function array_pop;

/**
 * Class representing the abstract simpleType.
 *
 * @package simplesamlphp/xml-common
 */
final class TopLevelSimpleType extends AbstractTopLevelSimpleType implements
    RedefinableInterface,
    SchemaValidatableElementInterface
{
    use SchemaValidatableElementTrait;


    /** @var string */
    public const LOCALNAME = 'simpleType';


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

        $union = Union::getChildrenOfClass($xml);
        $xsList = XsList::getChildrenOfClass($xml);
        $restriction = Restriction::getChildrenOfClass($xml);

        $derivation = array_merge($union, $xsList, $restriction);
        Assert::minCount($derivation, 1, MissingElementException::class);
        Assert::maxCount($derivation, 1, TooManyElementsException::class);

        return new static(
            $derivation[0],
            self::getAttribute($xml, 'name', NCNameValue::class),
            self::getOptionalAttribute($xml, 'final', SimpleDerivationSetValue::class, null),
            array_pop($annotation),
            self::getOptionalAttribute($xml, 'id', IDValue::class, null),
            self::getAttributesNSFromXML($xml),
        );
    }
}
