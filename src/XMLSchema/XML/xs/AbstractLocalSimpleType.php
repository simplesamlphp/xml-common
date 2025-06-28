<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use SimpleSAML\XMLSchema\Type\Builtin\IDValue;

/**
 * Abstract class representing the abstract localSimpleType.
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractLocalSimpleType extends AbstractSimpleType
{
    /**
     * Annotated constructor
     *
     * @param \SimpleSAML\XMLSchema\XML\xs\SimpleDerivationInterface $derivation
     * @param \SimpleSAML\XMLSchema\XML\xs\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\Builtin\IDValue|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        SimpleDerivationInterface $derivation,
        ?Annotation $annotation = null,
        ?IDValue $id = null,
        array $namespacedAttributes = [],
    ) {
        parent::__construct(
            derivation: $derivation,
            annotation: $annotation,
            id: $id,
            namespacedAttributes: $namespacedAttributes,
        );
    }
}
