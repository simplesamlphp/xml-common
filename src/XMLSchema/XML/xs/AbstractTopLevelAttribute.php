<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use SimpleSAML\XMLSchema\Type\Builtin\{IDValue, NCNameValue, QNameValue, StringValue};
use SimpleSAML\XMLSchema\XML\xs\NamespaceEnum;

/**
 * Abstract class representing the topLevelAttribute-type.
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractTopLevelAttribute extends AbstractAttribute
{
    /** The namespace-attribute for the xs:anyAttribute element */
    public const XS_ANY_ATTR_NAMESPACE = NamespaceEnum::Other;


    /**
     * TopLevelAttribute constructor
     *
     * @param \SimpleSAML\XMLSchema\Type\Builtin\NCNameValue $name
     * @param \SimpleSAML\XMLSchema\Type\Builtin\QNameValue $type
     * @param \SimpleSAML\XMLSchema\Type\Builtin\StringValue|null $default
     * @param \SimpleSAML\XMLSchema\Type\Builtin\StringValue|null $fixed
     * @param \SimpleSAML\XMLSchema\XML\xs\LocalSimpleType|null $simpleType
     * @param \SimpleSAML\XMLSchema\XML\xs\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\Builtin\IDValue|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        NCNameValue $name,
        ?QNameValue $type = null,
        ?StringValue $default = null,
        ?StringValue $fixed = null,
        ?LocalSimpleType $simpleType = null,
        ?Annotation $annotation = null,
        ?IDValue $id = null,
        array $namespacedAttributes = [],
    ) {
        parent::__construct(
            name: $name,
            type: $type,
            default: $default,
            fixed: $fixed,
            simpleType: $simpleType,
            annotation: $annotation,
            id: $id,
            namespacedAttributes: $namespacedAttributes,
        );
    }
}
