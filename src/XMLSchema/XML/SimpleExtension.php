<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML;

use Dom;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\InvalidDOMElementException;
use SimpleSAML\XMLSchema\Exception\TooManyElementsException;
use SimpleSAML\XMLSchema\Type\IDValue;
use SimpleSAML\XMLSchema\Type\QNameValue;

use function array_last;
use function array_merge;

/**
 * Class representing the simple version of the xs:extension.
 *
 * @package simplesamlphp/xml-common
 */
final class SimpleExtension extends AbstractSimpleExtensionType
{
    public const string LOCALNAME = 'extension';


    /**
     * Create an instance of this object from its XML representation.
     *
     * @param \Dom\Element $xml
     * @return static
     *
     * @throws \SimpleSAML\XMLSchema\Exception\InvalidDOMElementException
     *   if the qualified name of the supplied element is wrong
     */
    public static function fromXML(Dom\Element $xml): static
    {
        Assert::same($xml->localName, static::getLocalName(), InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, static::NS, InvalidDOMElementException::class);

        $annotation = Annotation::getChildrenOfClass($xml);
        Assert::maxCount($annotation, 1, TooManyElementsException::class);

        $localAttribute = LocalAttribute::getChildrenOfClass($xml);
        $attributeGroup = ReferencedAttributeGroup::getChildrenOfClass($xml);
        $attributes = array_merge($localAttribute, $attributeGroup);

        $anyAttribute = AnyAttribute::getChildrenOfClass($xml);
        Assert::maxCount($anyAttribute, 1, TooManyElementsException::class);

        return new static(
            self::getAttribute($xml, 'base', QNameValue::class),
            $attributes,
            array_last($anyAttribute),
            array_last($annotation),
            self::getOptionalAttribute($xml, 'id', IDValue::class, null),
            self::getAttributesNSFromXML($xml),
        );
    }
}
