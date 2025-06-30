<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML;

use SimpleSAML\XMLSchema\Type\{IDValue, NCNameValue, QNameValue, StringValue};
use SimpleSAML\XMLSchema\XML\Enumeration\NamespaceEnum;

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
     * @param \SimpleSAML\XMLSchema\Type\NCNameValue $name
     * @param \SimpleSAML\XMLSchema\Type\QNameValue $type
     * @param \SimpleSAML\XMLSchema\Type\StringValue|null $default
     * @param \SimpleSAML\XMLSchema\Type\StringValue|null $fixed
     * @param \SimpleSAML\XMLSchema\XML\LocalSimpleType|null $simpleType
     * @param \SimpleSAML\XMLSchema\XML\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\IDValue|null $id
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
