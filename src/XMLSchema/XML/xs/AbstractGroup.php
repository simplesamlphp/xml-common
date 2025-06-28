<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use DOMElement;
use SimpleSAML\XMLSchema\Type\Builtin\{IDValue, NCNameValue, QNameValue};
use SimpleSAML\XMLSchema\Type\{MinOccursValue, MaxOccursValue};

use function strval;

/**
 * Abstract class representing the group-type.
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractGroup extends AbstractAnnotated
{
    use DefRefTrait;
    use OccursTrait;

    /**
     * Group constructor
     *
     * @param \SimpleSAML\XMLSchema\Type\Builtin\NCNameValue|null $name
     * @param \SimpleSAML\XMLSchema\Type\Builtin\QNameValue|null $reference
     * @param \SimpleSAML\XMLSchema\Type\MinOccursValue|null $minOccurs
     * @param \SimpleSAML\XMLSchema\Type\MaxOccursValue|null $maxOccurs
     * @param array<\SimpleSAML\XMLSchema\XML\xs\ParticleInterface> $particles
     * @param \SimpleSAML\XMLSchema\XML\xs\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\Builtin\IDValue|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        ?NCNameValue $name = null,
        ?QNameValue $reference = null,
        ?MinOccursValue $minOccurs = null,
        ?MaxOccursValue $maxOccurs = null,
        protected array $particles = [],
        ?Annotation $annotation = null,
        ?IDValue $id = null,
        array $namespacedAttributes = [],
    ) {
        parent::__construct($annotation, $id, $namespacedAttributes);

        $this->setName($name);
        $this->setReference($reference);
        $this->setMinOccurs($minOccurs);
        $this->setMaxOccurs($maxOccurs);
    }


    /**
     * Collect the value of the particles-property
     *
     * @return array<\SimpleSAML\XMLSchema\XML\xs\ParticleInterface>
     */
    public function getParticles(): array
    {
        return $this->particles;
    }


    /**
     * Test if an object, at the state it's in, would produce an empty XML-element
     *
     * @return bool
     */
    public function isEmptyElement(): bool
    {
        return parent::isEmptyElement() &&
            empty($this->getParticles()) &&
            empty($this->getName()) &&
            empty($this->getReference()) &&
            empty($this->getMinOccurs()) &&
            empty($this->getMaxOccurs());
    }


    /**
     * Add this Group to an XML element.
     *
     * @param \DOMElement|null $parent The element we should append this Group to.
     * @return \DOMElement
     */
    public function toXML(?DOMElement $parent = null): DOMElement
    {
        $e = parent::toXML($parent);

        if ($this->getName() !== null) {
            $e->setAttribute('name', strval($this->getName()));
        }

        if ($this->getReference() !== null) {
            $e->setAttribute('ref', strval($this->getReference()));
        }

        if ($this->getMinOccurs() !== null) {
            $e->setAttribute('minOccurs', strval($this->getMinOccurs()));
        }

        if ($this->getMaxOccurs() !== null) {
            $e->setAttribute('maxOccurs', strval($this->getMaxOccurs()));
        }

        foreach ($this->getParticles() as $particle) {
            $particle->toXML($e);
        }

        return $e;
    }
}
