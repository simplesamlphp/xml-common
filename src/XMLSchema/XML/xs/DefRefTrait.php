<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use SimpleSAML\XMLSchema\Type\Builtin\{NCNameValue, QNameValue};

/**
 * Trait grouping common functionality for elements that use the defRef-attributeGroup.
 *
 * @package simplesamlphp/xml-common
 */
trait DefRefTrait
{
    /**
     * The name.
     *
     * @var \SimpleSAML\XMLSchema\Type\Builtin\NCNameValue|null
     */
    protected ?NCNameValue $name = null;


    /**
     * The reference.
     *
     * @var \SimpleSAML\XMLSchema\Type\Builtin\QNameValue|null
     */
    protected ?QNameValue $reference = null;


    /**
     * Collect the value of the name-property
     *
     * @return \SimpleSAML\XMLSchema\Type\Builtin\NCNameValue|null
     */
    public function getName(): ?NCNameValue
    {
        return $this->name;
    }


    /**
     * Set the value of the name-property
     *
     * @param \SimpleSAML\XMLSchema\Type\Builtin\NCNameValue|null $name
     */
    protected function setName(?NCNameValue $name): void
    {
        $this->name = $name;
    }


    /**
     * Collect the value of the reference-property
     *
     * @return \SimpleSAML\XMLSchema\Type\Builtin\QNameValue|null
     */
    public function getReference(): ?QNameValue
    {
        return $this->reference;
    }


    /**
     * Set the value of the reference-property
     *
     * @param \SimpleSAML\XMLSchema\Type\Builtin\QNameValue|null $reference
     */
    protected function setReference(?QNameValue $reference): void
    {
        $this->reference = $reference;
    }
}
