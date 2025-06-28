<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Builtin\IDValue;
use SimpleSAML\XMLSchema\Type\{MinOccursValue, MaxOccursValue};
use SimpleSAML\XMLSchema\XML\xs\NamespaceEnum;

/**
 * Abstract class representing the explicitGroup-type.
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractAll extends AbstractExplicitGroup
{
    /** The namespace-attribute for the xs:anyAttribute element */
    public const XS_ANY_ATTR_NAMESPACE = NamespaceEnum::Other;


    /**
     * All constructor
     *
     * @param \SimpleSAML\XMLSchema\Type\MinOccursValue|null $minOccurs
     * @param \SimpleSAML\XMLSchema\Type\MaxOccursValue|null $maxOccurs
     * @param \SimpleSAML\XMLSchema\XML\xs\NarrowMaxMinElement[] $particles
     * @param \SimpleSAML\XMLSchema\XML\xs\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\Builtin\IDValue|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        ?MinOccursValue $minOccurs = null,
        ?MaxOccursValue $maxOccurs = null,
        array $particles = [],
        ?Annotation $annotation = null,
        ?IDValue $id = null,
        array $namespacedAttributes = [],
    ) {
        if ($minOccurs !== null) {
            Assert::oneOf($minOccurs->toInteger(), [0, 1], SchemaViolationException::class);
        }

        if ($maxOccurs !== null) {
            Assert::same($maxOccurs->toInteger(), 1, SchemaViolationException::class);
        }

        Assert::allIsInstanceOf(
            $particles,
            NarrowMaxMinElement::class,
            SchemaViolationException::class,
        );

        parent::__construct(
            nestedParticles: $particles,
            minOccurs: $minOccurs,
            maxOccurs: $maxOccurs,
            annotation: $annotation,
            id: $id,
            namespacedAttributes: $namespacedAttributes,
        );
    }
}
