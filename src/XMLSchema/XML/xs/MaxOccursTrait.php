<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use SimpleSAML\XMLSchema\Type\MaxOccursValue;

/**
 * Trait grouping common functionality for elements that can hold a maxOccurs attribute.
 *
 * @package simplesamlphp/xml-common
 */
trait MaxOccursTrait
{
    /**
     * The maxOccurs.
     *
     * @var \SimpleSAML\XMLSchema\Type\MaxOccursValue|null
     */
    protected ?MaxOccursValue $maxOccurs = null;


    /**
     * Collect the value of the maxOccurs-property
     *
     * @return \SimpleSAML\XMLSchema\Type\MaxOccursValue|null
     */
    public function getMaxOccurs(): ?MaxOccursValue
    {
        return $this->maxOccurs;
    }


    /**
     * Set the value of the maxOccurs-property
     *
     * @param \SimpleSAML\XMLSchema\Type\MaxOccursValue|null $maxOccurs
     */
    protected function setMaxOccurs(?MaxOccursValue $maxOccurs): void
    {
        $this->maxOccurs = $maxOccurs;
    }
}
