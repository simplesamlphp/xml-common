<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use SimpleSAML\XMLSchema\Type\Builtin\IDValue;
use SimpleSAML\XMLSchema\Type\{MinOccursValue, MaxOccursValue};
use SimpleSAML\XMLSchema\XML\xs\NamespaceEnum;

/**
 * Abstract class representing the explicitGroup-type.
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractExplicitGroup extends AbstractGroup
{
    /** The namespace-attribute for the xs:anyAttribute element */
    public const XS_ANY_ATTR_NAMESPACE = NamespaceEnum::Other;


    /**
     * Group constructor
     *
     * @param \SimpleSAML\XMLSchema\Type\MinOccursValue|null $minOccurs
     * @param \SimpleSAML\XMLSchema\Type\MaxOccursValue|null $maxOccurs
     * @param array<\SimpleSAML\XMLSchema\XML\xs\NestedParticleInterface> $nestedParticles
     * @param \SimpleSAML\XMLSchema\XML\xs\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\Builtin\IDValue|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        ?MinOccursValue $minOccurs = null,
        ?MaxOccursValue $maxOccurs = null,
        array $nestedParticles = [],
        ?Annotation $annotation = null,
        ?IDValue $id = null,
        array $namespacedAttributes = [],
    ) {
        parent::__construct(
            particles: $nestedParticles,
            minOccurs: $minOccurs,
            maxOccurs: $maxOccurs,
            annotation: $annotation,
            id: $id,
            namespacedAttributes: $namespacedAttributes,
        );
    }
}
