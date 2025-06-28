<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use SimpleSAML\XMLSchema\Type\Builtin\IDValue;
use SimpleSAML\XMLSchema\XML\xs\NamespaceEnum;

/**
 * Abstract class representing the simpleExplicitGroup-type.
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractSimpleExplicitGroup extends AbstractExplicitGroup
{
    /** The namespace-attribute for the xs:anyAttribute element */
    public const XS_ANY_ATTR_NAMESPACE = NamespaceEnum::Other;


    /**
     * Group constructor
     *
     * @param array<\SimpleSAML\XMLSchema\XML\xs\NestedParticleInterface> $nestedParticles
     * @param \SimpleSAML\XMLSchema\XML\xs\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\Builtin\IDValue|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        array $nestedParticles = [],
        ?Annotation $annotation = null,
        ?IDValue $id = null,
        array $namespacedAttributes = [],
    ) {
        parent::__construct(
            nestedParticles: $nestedParticles,
            annotation: $annotation,
            id: $id,
            namespacedAttributes: $namespacedAttributes,
        );
    }
}
