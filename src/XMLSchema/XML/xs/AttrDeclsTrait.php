<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;

/**
 * Trait grouping common functionality for elements that use the attrDecls-group.
 *
 * @package simplesamlphp/xml-common
 */
trait AttrDeclsTrait
{
    /**
     * The attributes + groups.
     *
     * @var (
     *     \SimpleSAML\XMLSchema\XML\xs\LocalAttribute|
     *     \SimpleSAML\XMLSchema\XML\xs\ReferencedAttributeGroup
     * )[] $attributes
     */
    protected array $attributes = [];

    /**
     * The AnyAttribute
     *
     * @var \SimpleSAML\XMLSchema\XML\xs\AnyAttribute|null $anyAttribute
     */
    protected ?AnyAttribute $anyAttribute = null;


    /**
     * Collect the value of the attributes-property
     *
     * @return (
     *     \SimpleSAML\XMLSchema\XML\xs\LocalAttribute|
     *     \SimpleSAML\XMLSchema\XML\xs\ReferencedAttributeGroup
     * )[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }


    /**
     * Collect the value of the anyAttribute-property
     *
     * @return \SimpleSAML\XMLSchema\XML\xs\AnyAttribute|null
     */
    public function getAnyAttribute(): ?AnyAttribute
    {
        return $this->anyAttribute;
    }


    /**
     * Set the value of the attributes-property
     *
     * @param (
     *     \SimpleSAML\XMLSchema\XML\xs\LocalAttribute|
     *     \SimpleSAML\XMLSchema\XML\xs\ReferencedAttributeGroup
     * )[] $attributes
     */
    protected function setAttributes(array $attributes): void
    {
        Assert::allIsInstanceOfAny(
            $attributes,
            [LocalAttribute::class, ReferencedAttributeGroup::class],
            SchemaViolationException::class,
        );

        $this->attributes = $attributes;
    }


    /**
     * Set the value of the anyAttribute-property
     *
     * @param \SimpleSAML\XMLSchema\XML\xs\AnyAttribute|null $anyAttribute
     */
    protected function setAnyAttribute(?AnyAttribute $anyAttribute): void
    {
        $this->anyAttribute = $anyAttribute;
    }
}
