<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use DOMElement;
use SimpleSAML\XMLSchema\Type\Builtin\{IDValue, QNameValue};

use function strval;

/**
 * Abstract class representing the extensionType-type.
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractExtensionType extends AbstractAnnotated
{
    use AttrDeclsTrait;
    use TypeDefParticleTrait;

    /**
     * AbstractExtensionType constructor
     *
     * @param \SimpleSAML\XMLSchema\Type\Builtin\QNameValue $base
     * @param \SimpleSAML\XMLSchema\XML\xs\TypeDefParticleInterface|null $particle
     * @param (
     *   \SimpleSAML\XMLSchema\XML\xs\LocalAttribute|
     *   \SimpleSAML\XMLSchema\XML\xs\ReferencedAttributeGroup
     * )[] $attributes
     * @param \SimpleSAML\XMLSchema\XML\xs\AnyAttribute|null $anyAttribute
     * @param \SimpleSAML\XMLSchema\XML\xs\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\Builtin\IDValue|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        protected QNameValue $base,
        // xs:typeDefParticle
        ?TypeDefParticleInterface $particle = null,
        // xs:attrDecls
        array $attributes = [],
        ?AnyAttribute $anyAttribute = null,
        // parent defined
        ?Annotation $annotation = null,
        ?IDValue $id = null,
        array $namespacedAttributes = [],
    ) {
        parent::__construct($annotation, $id, $namespacedAttributes);

        $this->setAttributes($attributes);
        $this->setAnyAttribute($anyAttribute);
        $this->setParticle($particle);
    }


    /**
     * Collect the value of the base-property
     *
     * @return \SimpleSAML\XMLSchema\Type\Builtin\QNameValue
     */
    public function getBase(): ?QNameValue
    {
        return $this->base;
    }


    /**
     * Add this ExtensionType to an XML element.
     *
     * @param \DOMElement|null $parent The element we should append this ExtensionType to.
     * @return \DOMElement
     */
    public function toXML(?DOMElement $parent = null): DOMElement
    {
        $e = parent::toXML($parent);

        if ($this->getBase() !== null) {
            $e->setAttribute('base', strval($this->getBase()));
        }

        $this->getParticle()?->toXML($e);

        foreach ($this->getAttributes() as $attr) {
            $attr->toXML($e);
        }

        $this->getAnyAttribute()?->toXML($e);

        return $e;
    }
}
