<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use DOMElement;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\{ProtocolViolationException, SchemaViolationException};
use SimpleSAML\XMLSchema\Type\Builtin\{BooleanValue, IDValue, NCNameValue, QNameValue, StringValue};
use SimpleSAML\XMLSchema\Type\{BlockSetValue, DerivationSetValue, FormChoiceValue, MaxOccursValue, MinOccursValue};

use function strval;

/**
 * Abstract class representing the element-type.
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractElement extends AbstractAnnotated
{
    use DefRefTrait;
    use OccursTrait;

    /**
     * Element constructor
     *
     * @param \SimpleSAML\XMLSchema\Type\Builtin\NCNameValue|null $name
     * @param \SimpleSAML\XMLSchema\Type\Builtin\QNameValue|null $reference
     * @param \SimpleSAML\XMLSchema\XML\xs\LocalSimpleType|\SimpleSAML\XMLSchema\XML\xs\LocalComplexType|null $localType
     * @param array<\SimpleSAML\XMLSchema\XML\xs\IdentityConstraintInterface> $identityConstraint
     * @param \SimpleSAML\XMLSchema\Type\Builtin\QNameValue|null $type
     * @param \SimpleSAML\XMLSchema\Type\Builtin\QNameValue|null $substitutionGroup
     * @param \SimpleSAML\XMLSchema\Type\MinOccursValue|null $minOccurs
     * @param \SimpleSAML\XMLSchema\Type\MaxOccursValue|null $maxOccurs
     * @param \SimpleSAML\XMLSchema\Type\Builtin\StringValue|null $default
     * @param \SimpleSAML\XMLSchema\Type\Builtin\StringValue|null $fixed
     * @param \SimpleSAML\XMLSchema\Type\Builtin\BooleanValue|null $nillable
     * @param \SimpleSAML\XMLSchema\Type\Builtin\BooleanValue|null $abstract
     * @param \SimpleSAML\XMLSchema\Type\DerivationSetValue|null $final
     * @param \SimpleSAML\XMLSchema\Type\BlockSetValue|null $block
     * @param \SimpleSAML\XMLSchema\Type\FormChoiceValue|null $form
     * @param \SimpleSAML\XMLSchema\XML\xs\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\Builtin\IDValue|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        ?NCNameValue $name = null,
        ?QNameValue $reference = null,
        protected LocalSimpleType|LocalComplexType|null $localType = null,
        protected array $identityConstraint = [],
        protected ?QNameValue $type = null,
        protected ?QNameValue $substitutionGroup = null,
        ?MinOccursValue $minOccurs = null,
        ?MaxOccursValue $maxOccurs = null,
        protected ?StringValue $default = null,
        protected ?StringValue $fixed = null,
        protected ?BooleanValue $nillable = null,
        protected ?BooleanValue $abstract = null,
        protected ?DerivationSetValue $final = null,
        protected ?BlockSetValue $block = null,
        protected ?FormChoiceValue $form = null,
        ?Annotation $annotation = null,
        ?IDValue $id = null,
        array $namespacedAttributes = [],
    ) {
        Assert::allIsInstanceOf(
            $identityConstraint,
            IdentityConstraintInterface::class,
            SchemaViolationException::class,
        );

        /**
         * An element is declared by either: a name and a type (either nested or referenced via the type attribute)
         * or a ref to an existing element declaration
         *
         * type and ref are mutually exclusive.
         * name and ref are mutually exclusive, one is required
         */
        Assert::true(is_null($type) || is_null($reference), ProtocolViolationException::class);
        Assert::true(is_null($name) || is_null($reference), ProtocolViolationException::class);
        Assert::false(is_null($name) && is_null($reference), ProtocolViolationException::class);

        /**
         * default and fixed are mutually exclusive
         */
        Assert::true(is_null($default) || is_null($fixed), ProtocolViolationException::class);

        parent::__construct($annotation, $id, $namespacedAttributes);

        $this->setName($name);
        $this->setReference($reference);
        $this->setMinOccurs($minOccurs);
        $this->setMaxOccurs($maxOccurs);
    }


    /**
     * Collect the value of the localType-property
     *
     * @return \SimpleSAML\XMLSchema\XML\xs\LocalSimpleType|\SimpleSAML\XMLSchema\XML\xs\LocalComplexType|null
     */
    public function getLocalType(): LocalSimpleType|LocalComplexType|null
    {
        return $this->localType;
    }


    /**
     * Collect the value of the identityConstraint-property
     *
     * @return array<\SimpleSAML\XMLSchema\XML\xs\IdentityConstraintInterface>
     */
    public function getIdentityConstraint(): array
    {
        return $this->identityConstraint;
    }


    /**
     * Collect the value of the type-property
     *
     * @return \SimpleSAML\XMLSchema\Type\Builtin\QNameValue|null
     */
    public function getType(): ?QNameValue
    {
        return $this->type;
    }


    /**
     * Collect the value of the substitutionGroup-property
     *
     * @return \SimpleSAML\XMLSchema\Type\Builtin\QNameValue|null
     */
    public function getSubstitutionGroup(): ?QNameValue
    {
        return $this->substitutionGroup;
    }


    /**
     * Collect the value of the default-property
     *
     * @return \SimpleSAML\XMLSchema\Type\Builtin\StringValue|null
     */
    public function getDefault(): ?StringValue
    {
        return $this->default;
    }


    /**
     * Collect the value of the fixed-property
     *
     * @return \SimpleSAML\XMLSchema\Type\Builtin\StringValue|null
     */
    public function getFixed(): ?StringValue
    {
        return $this->fixed;
    }


    /**
     * Collect the value of the nillable-property
     *
     * @return \SimpleSAML\XMLSchema\Type\Builtin\BooleanValue|null
     */
    public function getNillable(): ?BooleanValue
    {
        return $this->nillable;
    }


    /**
     * Collect the value of the abstract-property
     *
     * @return \SimpleSAML\XMLSchema\Type\Builtin\BooleanValue|null
     */
    public function getAbstract(): ?BooleanValue
    {
        return $this->abstract;
    }


    /**
     * Collect the value of the final-property
     *
     * @return \SimpleSAML\XMLSchema\Type\DerivationSetValue|null
     */
    public function getFinal(): ?DerivationSetValue
    {
        return $this->final;
    }


    /**
     * Collect the value of the block-property
     *
     * @return \SimpleSAML\XMLSchema\Type\BlockSetValue|null
     */
    public function getBlock(): ?BlockSetValue
    {
        return $this->block;
    }


    /**
     * Collect the value of the form-property
     *
     * @return \SimpleSAML\XMLSchema\Type\FormChoiceValue|null
     */
    public function getForm(): ?FormChoiceValue
    {
        return $this->form;
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
            empty($this->getLocalType()) &&
            empty($this->getIdentityConstraint()) &&
            empty($this->getType()) &&
            empty($this->getSubstitutionGroup()) &&
            empty($this->getMinOccurs()) &&
            empty($this->getMaxOccurs()) &&
            empty($this->getDefault()) &&
            empty($this->getFixed()) &&
            empty($this->getNillable()) &&
            empty($this->getAbstract()) &&
            empty($this->getFinal()) &&
            empty($this->getBlock()) &&
            empty($this->getForm());
    }


    /**
     * Add this Annotated to an XML element.
     *
     * @param \DOMElement|null $parent The element we should append this Annotated to.
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

        if ($this->getType() !== null) {
            $e->setAttribute('type', strval($this->getType()));
        }

        if ($this->getSubstitutionGroup() !== null) {
            $e->setAttribute('substitutionGroup', strval($this->getSubstitutionGroup()));
        }

        if ($this->getMinOccurs() !== null) {
            $e->setAttribute('minOccurs', strval($this->getMinOccurs()));
        }

        if ($this->getMaxOccurs() !== null) {
            $e->setAttribute('maxOccurs', strval($this->getMaxOccurs()));
        }

        if ($this->getDefault() !== null) {
            $e->setAttribute('default', strval($this->getDefault()));
        }

        if ($this->getFixed() !== null) {
            $e->setAttribute('fixed', strval($this->getFixed()));
        }

        if ($this->getNillable() !== null) {
            $e->setAttribute('nillable', strval($this->getNillable()));
        }

        if ($this->getAbstract() !== null) {
            $e->setAttribute('abstract', strval($this->getAbstract()));
        }

        if ($this->getFinal() !== null) {
            $e->setAttribute('final', strval($this->getFinal()));
        }

        if ($this->getBlock() !== null) {
            $e->setAttribute('block', strval($this->getBlock()));
        }

        if ($this->getForm() !== null) {
            $e->setAttribute('form', strval($this->getForm()));
        }

        $this->getLocalType()?->toXML($e);

        foreach ($this->getIdentityConstraint() as $identityConstraint) {
            $identityConstraint->toXML($e);
        }

        return $e;
    }
}
