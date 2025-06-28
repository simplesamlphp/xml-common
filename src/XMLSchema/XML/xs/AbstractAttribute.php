<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use DOMElement;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\ProtocolViolationException;
use SimpleSAML\XMLSchema\Type\Builtin\{IDValue, NCNameValue, QNameValue, StringValue};
use SimpleSAML\XMLSchema\Type\{FormChoiceValue, UseValue};

use function strval;

/**
 * Abstract class representing the attribute-type.
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractAttribute extends AbstractAnnotated
{
    use DefRefTrait;
    use FormChoiceTrait;


    /**
     * Attribute constructor
     *
     * @param \SimpleSAML\XMLSchema\Type\Builtin\QNameValue|null $type
     * @param \SimpleSAML\XMLSchema\Type\Builtin\NCNameValue|null $name
     * @param \SimpleSAML\XMLSchema\Type\Builtin\QNameValue|null $reference
     * @param \SimpleSAML\XMLSchema\Type\UseValue|null $use
     * @param \SimpleSAML\XMLSchema\Type\Builtin\StringValue|null $default
     * @param \SimpleSAML\XMLSchema\Type\Builtin\StringValue|null $fixed
     * @param \SimpleSAML\XMLSchema\Type\FormChoiceValue|null $formChoice
     * @param \SimpleSAML\XMLSchema\XML\xs\LocalSimpleType|null $simpleType
     * @param \SimpleSAML\XMLSchema\XML\xs\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\Builtin\IDValue|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        protected ?QNameValue $type = null,
        ?NCNameValue $name = null,
        ?QNameValue $reference = null,
        protected ?UseValue $use = null,
        protected ?StringValue $default = null,
        protected ?StringValue $fixed = null,
        ?FormChoiceValue $formChoice = null,
        protected ?LocalSimpleType $simpleType = null,
        ?Annotation $annotation = null,
        ?IDValue $id = null,
        array $namespacedAttributes = [],
    ) {
        Assert::true(is_null($type) || is_null($reference), ProtocolViolationException::class);
        Assert::true(is_null($name) || is_null($reference), ProtocolViolationException::class);
        Assert::false(is_null($name) && is_null($reference), ProtocolViolationException::class);

        parent::__construct($annotation, $id, $namespacedAttributes);

        $this->setName($name);
        $this->setReference($reference);
        $this->setFormChoice($formChoice);
    }


    /**
     * Collect the value of the simpleType-property
     *
     * @return \SimpleSAML\XMLSchema\XML\xs\LocalSimpleType|null
     */
    public function getSimpleType(): ?LocalSimpleType
    {
        return $this->simpleType;
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
     * Collect the value of the use-property
     *
     * @return \SimpleSAML\XMLSchema\Type\UseValue|null
     */
    public function getUse(): ?UseValue
    {
        return $this->use;
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
     * Add this Attribute to an XML element.
     *
     * @param \DOMElement|null $parent The element we should append this facet to.
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

        if ($this->getUse() !== null) {
            $e->setAttribute('use', strval($this->getUse()));
        }

        if ($this->getDefault() !== null) {
            $e->setAttribute('default', strval($this->getDefault()));
        }

        if ($this->getFixed() !== null) {
            $e->setAttribute('fixed', strval($this->getFixed()));
        }

        if ($this->getFormChoice() !== null) {
            $e->setAttribute('form', strval($this->getFormChoice()));
        }

        $this->getSimpleType()?->toXML($e);

        return $e;
    }
}
