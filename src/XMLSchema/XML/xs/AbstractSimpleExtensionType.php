<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use SimpleSAML\XMLSchema\Type\Builtin\{IDValue, QNameValue};

/**
 * Abstract class representing the simpleExtensionType-type.
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractSimpleExtensionType extends AbstractExtensionType
{
    /**
     * AbstractExtensionType constructor
     *
     * @param \SimpleSAML\XMLSchema\Type\Builtin\QNameValue $base
     * @param (
     *     \SimpleSAML\XMLSchema\XML\xs\LocalAttribute|
     *     \SimpleSAML\XMLSchema\XML\xs\ReferencedAttributeGroup
     * )[] $attributes
     * @param \SimpleSAML\XMLSchema\XML\xs\AnyAttribute|null $anyAttribute
     * @param \SimpleSAML\XMLSchema\XML\xs\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\Builtin\IDValue|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        QNameValue $base,
        // xs:attrDecls
        array $attributes = [],
        ?AnyAttribute $anyAttribute = null,
        // parent defined
        ?Annotation $annotation = null,
        ?IDValue $id = null,
        array $namespacedAttributes = [],
    ) {
        parent::__construct(
            base: $base,
            attributes: $attributes,
            anyAttribute: $anyAttribute,
            annotation: $annotation,
            id: $id,
            namespacedAttributes: $namespacedAttributes,
        );
    }
}
