<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML;

use Dom;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\SchemaValidatableElementInterface;
use SimpleSAML\XML\SchemaValidatableElementTrait;
use SimpleSAML\XMLSchema\Exception\InvalidDOMElementException;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Exception\TooManyElementsException;
use SimpleSAML\XMLSchema\Type\IDValue;
use SimpleSAML\XMLSchema\Type\StringValue;

use function array_last;
use function strval;

/**
 * Class representing the selector-element.
 *
 * @package simplesamlphp/xml-common
 */
final class Selector extends AbstractAnnotated implements SchemaValidatableElementInterface
{
    use SchemaValidatableElementTrait;


    public const string LOCALNAME = 'selector';


    public static string $selector_regex = '/^
        (
            (\.\/\/)?
            (((child::)?(([_:A-Za-z][-._:A-Za-z0-9]*:)?([_:A-Za-z][-._:A-Za-z0-9]*|\*)))|\.)
            (\/(((child::)?(([_:A-Za-z][-._:A-Za-z0-9]*:)?([_:A-Za-z][-._:A-Za-z0-9]*|\*)))|\.))*
            (\|(\.\/\/)?(((child::)?(([_:A-Za-z][-._:A-Za-z0-9]*:)?([_:A-Za-z][-._:A-Za-z0-9]*|\*)))|\.)
            (\/(((child::)?(([_:A-Za-z][-._:A-Za-z0-9]*:)?([_:A-Za-z][-._:A-Za-z0-9]*|\*)))|\.))*)*
        )$/Dx';


    /**
     * Selector constructor
     *
     * @param \SimpleSAML\XMLSchema\Type\StringValue $xpath
     * @param \SimpleSAML\XMLSchema\XML\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\IDValue|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        protected StringValue $xpath,
        ?Annotation $annotation = null,
        ?IDValue $id = null,
        array $namespacedAttributes = [],
    ) {
        Assert::regex(strval($xpath), self::$selector_regex, SchemaViolationException::class);

        parent::__construct($annotation, $id, $namespacedAttributes);
    }


    /**
     * Collect the value of the xpath-property
     *
     * @return \SimpleSAML\XMLSchema\Type\StringValue
     */
    public function getXPath(): StringValue
    {
        return $this->xpath;
    }


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

        return new static(
            self::getAttribute($xml, 'xpath', StringValue::class),
            array_last($annotation),
            self::getOptionalAttribute($xml, 'id', IDValue::class, null),
            self::getAttributesNSFromXML($xml),
        );
    }


    /**
     * Add this Selector to an XML element.
     *
     * @param \Dom\Element|null $parent The element we should append this selector to.
     * @return \Dom\Element
     */
    public function toXML(?Dom\Element $parent = null): Dom\Element
    {
        $e = parent::toXML($parent);
        $e->setAttribute('xpath', strval($this->getXPath()));

        return $e;
    }
}
