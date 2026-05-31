<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML;

use Dom;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\SchemaValidatableElementInterface;
use SimpleSAML\XML\SchemaValidatableElementTrait;
use SimpleSAML\XMLSchema\Exception\InvalidDOMElementException;
use SimpleSAML\XMLSchema\Exception\TooManyElementsException;
use SimpleSAML\XMLSchema\Type\AnyURIValue;
use SimpleSAML\XMLSchema\Type\IDValue;
use SimpleSAML\XMLSchema\Type\NCNameValue;
use SimpleSAML\XMLSchema\Type\Schema\PublicValue;
use SimpleSAML\XMLSchema\XML\Interface\SchemaTopInterface;

use function strval;

/**
 * Class representing the notation-element.
 *
 * @package simplesamlphp/xml-common
 */
final class Notation extends AbstractAnnotated implements
    SchemaTopInterface,
    SchemaValidatableElementInterface
{
    use SchemaValidatableElementTrait;


    public const string LOCALNAME = 'notation';


    /**
     * Notation constructor
     *
     * @param \SimpleSAML\XMLSchema\Type\NCNameValue $name
     * @param \SimpleSAML\XMLSchema\Type\Schema\PublicValue|null $public
     * @param \SimpleSAML\XMLSchema\Type\AnyURIValue|null $system
     * @param \SimpleSAML\XMLSchema\XML\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\IDValue|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        protected NCNameValue $name,
        protected ?PublicValue $public = null,
        protected ?AnyURIValue $system = null,
        ?Annotation $annotation = null,
        ?IDValue $id = null,
        array $namespacedAttributes = [],
    ) {
        parent::__construct($annotation, $id, $namespacedAttributes);
    }


    /**
     * Collect the value of the name-property
     *
     * @return \SimpleSAML\XMLSchema\Type\NCNameValue
     */
    public function getName(): NCNameValue
    {
        return $this->name;
    }


    /**
     * Collect the value of the public-property
     *
     * @return \SimpleSAML\XMLSchema\Type\Schema\PublicValue|null
     */
    public function getPublic(): ?PublicValue
    {
        return $this->public;
    }


    /**
     * Collect the value of the system-property
     *
     * @return \SimpleSAML\XMLSchema\Type\AnyURIValue|null
     */
    public function getSystem(): ?AnyURIValue
    {
        return $this->system;
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
            self::getAttribute($xml, 'name', NCNameValue::class),
            self::getOptionalAttribute($xml, 'public', PublicValue::class, null),
            self::getOptionalAttribute($xml, 'system', AnyURIValue::class, null),
            array_pop($annotation),
            self::getOptionalAttribute($xml, 'id', IDValue::class, null),
            self::getAttributesNSFromXML($xml),
        );
    }


    /**
     * Add this Notation to an XML element.
     *
     * @param \Dom\Element|null $parent The element we should append this notation to.
     * @return \Dom\Element
     */
    public function toXML(?Dom\Element $parent = null): Dom\Element
    {
        $e = parent::toXML($parent);

        $e->setAttribute('name', strval($this->getName()));

        if ($this->getPublic() !== null) {
            $e->setAttribute('public', strval($this->getPublic()));
        }

        if ($this->getSystem() !== null) {
            $e->setAttribute('system', strval($this->getSystem()));
        }

        return $e;
    }
}
