<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML;

use Dom;
use SimpleSAML\XMLSchema\Type\IDValue;
use SimpleSAML\XMLSchema\Type\NCNameValue;
use SimpleSAML\XMLSchema\Type\QNameValue;
use SimpleSAML\XMLSchema\XML\Trait\AttrDeclsTrait;
use SimpleSAML\XMLSchema\XML\Trait\DefRefTrait;

use function strval;

/**
 * Abstract class representing the attributeGroup-type.
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractAttributeGroup extends AbstractAnnotated
{
    use AttrDeclsTrait;
    use DefRefTrait;


    /**
     * AttributeGroup constructor
     *
     * @param (
     *   \SimpleSAML\XMLSchema\XML\LocalAttribute|
     *   \SimpleSAML\XMLSchema\XML\ReferencedAttributeGroup
     * )[] $attributes
     * @param \SimpleSAML\XMLSchema\XML\AnyAttribute|null $anyAttribute
     * @param \SimpleSAML\XMLSchema\Type\NCNameValue|null $name
     * @param \SimpleSAML\XMLSchema\Type\QNameValue|null $reference
     * @param \SimpleSAML\XMLSchema\XML\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\IDValue|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        array $attributes = [],
        ?AnyAttribute $anyAttribute = null,
        ?NCNameValue $name = null,
        ?QNameValue $reference = null,
        protected ?Annotation $annotation = null,
        protected ?IDValue $id = null,
        array $namespacedAttributes = [],
    ) {
        parent::__construct($annotation, $id, $namespacedAttributes);

        $this->setAttributes($attributes);
        $this->setAnyAttribute($anyAttribute);
        $this->setName($name);
        $this->setReference($reference);
    }


    /**
     * Test if an object, at the state it's in, would produce an empty XML-element
     *
     * @return bool
     */
    public function isEmptyElement(): bool
    {
        return parent::isEmptyElement() &&
            empty($this->getName()) &&
            empty($this->getReference()) &&
            empty($this->getAttributes()) &&
            empty($this->getAnyAttribute());
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

        if ($this->getName() !== null) {
            $e->setAttribute('name', strval($this->getName()));
        }

        if ($this->getReference() !== null) {
            $e->setAttribute('ref', strval($this->getReference()));
        }

        foreach ($this->getAttributes() as $attr) {
            $attr->toXML($e);
        }

        $this->getAnyAttribute()?->toXML($e);

        return $e;
    }
}
