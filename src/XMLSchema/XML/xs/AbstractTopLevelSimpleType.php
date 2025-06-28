<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Builtin\{IDValue, NCNameValue};
use SimpleSAML\XMLSchema\Type\SimpleDerivationSetValue;

/**
 * Abstract class representing the abstract topLevelSimpleType.
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractTopLevelSimpleType extends AbstractSimpleType
{
    /**
     * TopLevelSimpleType constructor
     *
     * @param (
     *   \SimpleSAML\XMLSchema\XML\xs\Union|
     *   \SimpleSAML\XMLSchema\XML\xs\XsList|
     *   \SimpleSAML\XMLSchema\XML\xs\Restriction
     * ) $derivation
     * @param \SimpleSAML\XMLSchema\Type\Builtin\NCNameValue $name
     * @param \SimpleSAML\XMLSchema\Type\SimpleDerivationSetValue $final
     * @param \SimpleSAML\XMLSchema\XML\xs\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\Builtin\IDValue|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        Union|XsList|Restriction $derivation,
        NCNameValue $name,
        ?SimpleDerivationSetValue $final = null,
        ?Annotation $annotation = null,
        ?IDValue $id = null,
        array $namespacedAttributes = [],
    ) {
        Assert::notNull($name, SchemaViolationException::class);

        parent::__construct($derivation, $name, $final, $annotation, $id, $namespacedAttributes);
    }
}
