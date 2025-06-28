<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use SimpleSAML\XMLSchema\Type\Builtin\{BooleanValue, IDValue, NCNameValue, QNameValue, StringValue};
use SimpleSAML\XMLSchema\Type\{BlockSetValue, DerivationSetValue};

/**
 * Abstract class representing the topLevelElement-type.
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractTopLevelElement extends AbstractElement
{
    /**
     * Element constructor
     *
     * @param \SimpleSAML\XMLSchema\Type\Builtin\NCNameValue $name
     * @param \SimpleSAML\XMLSchema\XML\xs\LocalSimpleType|\SimpleSAML\XMLSchema\XML\xs\LocalComplexType|null $localType
     * @param array<\SimpleSAML\XMLSchema\XML\xs\IdentityConstraintInterface> $identityConstraint
     * @param \SimpleSAML\XMLSchema\Type\Builtin\QNameValue|null $type
     * @param \SimpleSAML\XMLSchema\Type\Builtin\QNameValue|null $substitutionGroup
     * @param \SimpleSAML\XMLSchema\Type\Builtin\StringValue|null $default
     * @param \SimpleSAML\XMLSchema\Type\Builtin\StringValue|null $fixed
     * @param \SimpleSAML\XMLSchema\Type\Builtin\BooleanValue|null $nillable
     * @param \SimpleSAML\XMLSchema\Type\Builtin\BooleanValue|null $abstract
     * @param \SimpleSAML\XMLSchema\Type\DerivationSetValue|null $final
     * @param \SimpleSAML\XMLSchema\Type\BlockSetValue|null $block
     * @param \SimpleSAML\XMLSchema\XML\xs\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\Builtin\IDValue|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        NCNameValue $name,
        LocalSimpleType|LocalComplexType|null $localType = null,
        array $identityConstraint = [],
        ?QNameValue $type = null,
        ?QNameValue $substitutionGroup = null,
        ?StringValue $default = null,
        ?StringValue $fixed = null,
        ?BooleanValue $nillable = null,
        ?BooleanValue $abstract = null,
        ?DerivationSetValue $final = null,
        ?BlockSetValue $block = null,
        ?Annotation $annotation = null,
        ?IDValue $id = null,
        array $namespacedAttributes = [],
    ) {
        parent::__construct(
            name: $name,
            localType: $localType,
            identityConstraint: $identityConstraint,
            type: $type,
            substitutionGroup: $substitutionGroup,
            default: $default,
            fixed: $fixed,
            nillable: $nillable,
            abstract: $abstract,
            final: $final,
            block: $block,
            annotation: $annotation,
            id: $id,
            namespacedAttributes: $namespacedAttributes,
        );
    }
}
