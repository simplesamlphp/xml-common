<?php

declare(strict_types=1);

namespace SimpleSAML\XML\xsd;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Exception\InvalidDOMElementException;
use SimpleSAML\XML\Exception\SchemaViolationException;

/**
 * Abstract class representing the annotated-type.
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractAnnotated extends AbstractOpenAttrs
{
    /**
     * Annotated constructor
     *
     * @param \SimpleSAML\XML\xsd\Annotation|null $annotation
     * @param string|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        protected ?Annotation $annotation = null,
        protected ?string $id,
        array $namespacedAttributes = [],
    ) {
        Assert::nullOrValidNCName($id, SchemaViolationException::class);

        parent::__construct($namespacedAttributes);
    }


    /**
     * Collect the value of the annotation-property
     *
     * @return \SimpleSAML\XML\xsd\Annotation|null
     */
    public function getAnnotation(): ?Annotation
    {
        return $this->annotation;
    }


    /**
     * Collect the value of the id-property
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }


    /**
     * Test if an object, at the state it's in, would produce an empty XML-element
     *
     * @return bool
     */
    public function isEmptyElement(): bool
    {
        return parent::isEmptyElement() &&
            empty($this->getAnnotation()) &&
            empty($this->id);
    }


    /**
     * Add this Annotated to an XML element.
     *
     * @param \DOMElement $parent The element we should append this Annotated to.
     * @return \DOMElement
     */
    public function toXML(DOMElement $parent = null): DOMElement
    {
        $e = parent::toXML($parent);

        if ($this->getId() !== null) {
            $e->setAttribute('id', $this->getId());
        }

        if ($this->getAnnotation() !== null) {
            $annotation->toXML($e);
        }

        return $e;
    }
}
