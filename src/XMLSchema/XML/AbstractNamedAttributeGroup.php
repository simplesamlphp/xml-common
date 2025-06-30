<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML;

use SimpleSAML\XMLSchema\Type\{IDValue, NCNameValue};
use SimpleSAML\XMLSchema\XML\Enumeration\NamespaceEnum;

/**
 * Abstract class representing the namedAttributeGroup-type.
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractNamedAttributeGroup extends AbstractAttributeGroup
{
    /** The namespace-attribute for the xs:anyAttribute element */
    public const XS_ANY_ATTR_NAMESPACE = NamespaceEnum::Other;


    /**
     * NamedAttributeGroup constructor
     *
     * @param \SimpleSAML\XMLSchema\Type\NCNameValue $name
     * @param (
     *     \SimpleSAML\XMLSchema\XML\LocalAttribute|
     *     \SimpleSAML\XMLSchema\XML\ReferencedAttributeGroup
     * )[] $attributes
     * @param \SimpleSAML\XMLSchema\XML\AnyAttribute|null $anyAttribute
     * @param \SimpleSAML\XMLSchema\XML\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\IDValue|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        NCNameValue $name,
        array $attributes = [],
        ?AnyAttribute $anyAttribute = null,
        ?Annotation $annotation = null,
        ?IDValue $id = null,
        array $namespacedAttributes = [],
    ) {
        parent::__construct(
            name: $name,
            attributes: $attributes,
            anyAttribute: $anyAttribute,
            reference: null,
            annotation: $annotation,
            id: $id,
            namespacedAttributes: $namespacedAttributes,
        );
    }
}
