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

use function array_last;
use function strval;

/**
 * Class representing the import-element.
 *
 * @package simplesamlphp/xml-common
 */
final class Import extends AbstractAnnotated implements SchemaValidatableElementInterface
{
    use SchemaValidatableElementTrait;


    public const string LOCALNAME = 'import';


    /**
     * Import constructor
     *
     * @param \SimpleSAML\XMLSchema\Type\AnyURIValue|null $namespace
     * @param \SimpleSAML\XMLSchema\Type\AnyURIValue|null $schemaLocation
     * @param \SimpleSAML\XMLSchema\XML\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\IDValue|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        protected ?AnyURIValue $namespace = null,
        protected ?AnyURIValue $schemaLocation = null,
        ?Annotation $annotation = null,
        ?IDValue $id = null,
        array $namespacedAttributes = [],
    ) {
        parent::__construct($annotation, $id, $namespacedAttributes);
    }


    /**
     * Collect the value of the namespace-property
     *
     * @return \SimpleSAML\XMLSchema\Type\AnyURIValue|null
     */
    public function getNamespace(): ?AnyURIValue
    {
        return $this->namespace;
    }


    /**
     * Collect the value of the schemaLocation-property
     *
     * @return \SimpleSAML\XMLSchema\Type\AnyURIValue|null
     */
    public function getSchemaLocation(): ?AnyURIValue
    {
        return $this->schemaLocation;
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
            self::getOptionalAttribute($xml, 'namespace', AnyURIValue::class, null),
            self::getOptionalAttribute($xml, 'schemaLocation', AnyURIValue::class, null),
            array_last($annotation),
            self::getOptionalAttribute($xml, 'id', IDValue::class, null),
            self::getAttributesNSFromXML($xml),
        );
    }


    /**
     * Add this Import to an XML element.
     *
     * @param \Dom\Element|null $parent The element we should append this import to.
     * @return \Dom\Element
     */
    public function toXML(?Dom\Element $parent = null): Dom\Element
    {
        $e = parent::toXML($parent);

        if ($this->getNamespace() !== null) {
            $e->setAttribute('namespace', strval($this->getNamespace()));
        }

        if ($this->getSchemaLocation() !== null) {
            $e->setAttribute('schemaLocation', strval($this->getSchemaLocation()));
        }

        return $e;
    }
}
