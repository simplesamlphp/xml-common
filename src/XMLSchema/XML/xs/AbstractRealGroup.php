<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Builtin\{IDValue, NCNameValue, QNameValue};
use SimpleSAML\XMLSchema\Type\{MinOccursValue, MaxOccursValue};
use SimpleSAML\XMLSchema\XML\xs\NamespaceEnum;

use function is_null;

/**
 * Abstract class representing the realGroup-type.
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractRealGroup extends AbstractGroup
{
    /** The namespace-attribute for the xs:anyAttribute element */
    public const XS_ANY_ATTR_NAMESPACE = NamespaceEnum::Other;


    /**
     * Group constructor
     *
     * @param \SimpleSAML\XMLSchema\XML\xs\ParticleInterface|null $particle
     * @param \SimpleSAML\XMLSchema\Type\Builtin\NCNameValue|null $name
     * @param \SimpleSAML\XMLSchema\Type\Builtin\QNameValue|null $reference
     * @param \SimpleSAML\XMLSchema\Type\MinOccursValue|null $minOccurs
     * @param \SimpleSAML\XMLSchema\Type\MaxOccursValue|null $maxOccurs
     * @param \SimpleSAML\XMLSchema\XML\xs\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\Builtin\IDValue|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        ?ParticleInterface $particle = null,
        ?NCNameValue $name = null,
        ?QNameValue $reference = null,
        ?MinOccursValue $minOccurs = null,
        ?MaxOccursValue $maxOccurs = null,
        ?Annotation $annotation = null,
        ?IDValue $id = null,
        array $namespacedAttributes = [],
    ) {
        Assert::nullOrIsInstanceOf(
            $particle,
            ParticleInterface::class,
            SchemaViolationException::class,
        );

        parent::__construct(
            $name,
            $reference,
            $minOccurs,
            $maxOccurs,
            is_null($particle) ? [] : [$particle],
            $annotation,
            $id,
            $namespacedAttributes,
        );
    }
}
