<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use SimpleSAML\XMLSchema\Type\Builtin\{BooleanValue, IDValue, NCNameValue};
use SimpleSAML\XMLSchema\Type\DerivationSetValue;

/**
 * Abstract class representing the topLevelComplexType-type.
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractTopLevelComplexType extends AbstractComplexType
{
    /**
     * TopLevelComplexType constructor
     *
     * @param \SimpleSAML\XMLSchema\Type\Builtin\NCNameValue $name
     * @param \SimpleSAML\XMLSchema\Type\Builtin\BooleanValue|null $mixed
     * @param \SimpleSAML\XMLSchema\Type\Builtin\BooleanValue|null $abstract
     * @param \SimpleSAML\XMLSchema\Type\DerivationSetValue|null $final
     * @param \SimpleSAML\XMLSchema\Type\DerivationSetValue|null $block
     * @param \SimpleSAML\XMLSchema\XML\xs\SimpleContent|\SimpleSAML\XMLSchema\XML\xs\ComplexContent|null $content
     * @param \SimpleSAML\XMLSchema\XML\xs\TypeDefParticleInterface|null $particle
     * @param (
     *     \SimpleSAML\XMLSchema\XML\xs\LocalAttribute|
     *     \SimpleSAML\XMLSchema\XML\xs\ReferencedAttributeGroup
     * )[] $attributes
     * @param \SimpleSAML\XMLSchema\XML\xs\AnyAttribute|null $anyAttribute
     * @param \SimpleSAML\XMLSchema\XML\xs\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\Builtin\IDValue|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        NCNameValue $name,
        ?BooleanValue $mixed = null,
        ?BooleanValue $abstract = null,
        ?DerivationSetValue $final = null,
        ?DerivationSetValue $block = null,
        SimpleContent|ComplexContent|null $content = null,
        ?TypeDefParticleInterface $particle = null,
        array $attributes = [],
        ?AnyAttribute $anyAttribute = null,
        ?Annotation $annotation = null,
        ?IDValue $id = null,
        array $namespacedAttributes = [],
    ) {
        parent::__construct(
            $name,
            $mixed,
            $abstract,
            $final,
            $block,
            $content,
            $particle,
            $attributes,
            $anyAttribute,
            $annotation,
            $id,
            $namespacedAttributes,
        );
    }
}
