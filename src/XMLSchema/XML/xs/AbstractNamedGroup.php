<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Builtin\{IDValue, NCNameValue};
use SimpleSAML\XMLSchema\XML\xs\NamespaceEnum;

/**
 * Abstract class representing the namedGroup-type.
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractNamedGroup extends AbstractRealGroup
{
    /** The namespace-attribute for the xs:anyAttribute element */
    public const XS_ANY_ATTR_NAMESPACE = NamespaceEnum::Other;


    /**
     * Group constructor
     *
     * @param \SimpleSAML\XMLSchema\XML\xs\ParticleInterface $particle
     * @param \SimpleSAML\XMLSchema\Type\Builtin\NCNameValue|null $name
     * @param \SimpleSAML\XMLSchema\XML\xs\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\Builtin\IDValue|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        ParticleInterface $particle,
        ?NCNameValue $name = null,
        ?Annotation $annotation = null,
        ?IDValue $id = null,
        array $namespacedAttributes = [],
    ) {
        Assert::isInstanceOfAny(
            $particle,
            [All::class, Choice::class, Sequence::class],
            SchemaViolationException::class,
        );

        if ($particle instanceof All) {
            Assert::null($particle->getMinOccurs(), SchemaViolationException::class);
            Assert::null($particle->getMaxOccurs(), SchemaViolationException::class);
        }

        parent::__construct(
            name: $name,
            particle: $particle,
            annotation: $annotation,
            id: $id,
            namespacedAttributes: $namespacedAttributes,
        );
    }
}
