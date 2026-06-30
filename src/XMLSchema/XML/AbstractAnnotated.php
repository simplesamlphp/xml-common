<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML;

use Dom;
use SimpleSAML\XMLSchema\Type\IDValue;

use function strval;

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
     * @param \SimpleSAML\XMLSchema\XML\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\IDValue|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        protected ?Annotation $annotation = null,
        protected ?IDValue $id = null,
        array $namespacedAttributes = [],
    ) {
        parent::__construct($namespacedAttributes);
    }


    /**
     * Collect the value of the annotation-property
     *
     * @return \SimpleSAML\XMLSchema\XML\Annotation|null
     */
    public function getAnnotation(): ?Annotation
    {
        return $this->annotation;
    }


    /**
     * Collect the value of the id-property
     *
     * @return \SimpleSAML\XMLSchema\Type\IDValue|null
     */
    public function getId(): ?IDValue
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
            empty($this->getId());
    }


    /**
     * Add this Annotated to an XML element.
     *
     * @param \Dom\Element|null $parent The element we should append this Annotated to.
     * @return \Dom\Element
     */
    public function toXML(?Dom\Element $parent = null): Dom\Element
    {
        $e = parent::toXML($parent);

        if ($this->getId() !== null) {
            $e->setAttribute('id', strval($this->getId()));
        }

        $this->getAnnotation()?->toXML($e);

        return $e;
    }
}
