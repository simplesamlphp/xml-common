<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Builtin\{BooleanValue, IDValue, NCNameValue, QNameValue, StringValue};
use SimpleSAML\XMLSchema\Type\{BlockSetValue, DerivationSetValue, FormChoiceValue, MaxOccursValue, MinOccursValue};

use function strval;

/**
 * Abstract class representing the narrowMaxMin-type.
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractNarrowMaxMin extends AbstractLocalElement
{
    /**
     * Element constructor
     *
     * @param \SimpleSAML\XMLSchema\Type\Builtin\NCNameValue|null $name
     * @param \SimpleSAML\XMLSchema\Type\Builtin\QNameValue|null $reference
     * @param \SimpleSAML\XMLSchema\XML\xs\LocalSimpleType|\SimpleSAML\XMLSchema\XML\xs\LocalComplexType|null $localType
     * @param array<\SimpleSAML\XMLSchema\XML\xs\IdentityConstraintInterface> $identityConstraint
     * @param \SimpleSAML\XMLSchema\Type\Builtin\QNameValue|null $type
     * @param \SimpleSAML\XMLSchema\Type\MinOccursValue|null $minOccurs
     * @param \SimpleSAML\XMLSchema\Type\MaxOccursValue|null $maxOccurs
     * @param \SimpleSAML\XMLSchema\Type\Builtin\StringValue|null $default
     * @param \SimpleSAML\XMLSchema\Type\Builtin\StringValue|null $fixed
     * @param \SimpleSAML\XMLSchema\Type\Builtin\BooleanValue|null $nillable
     * @param \SimpleSAML\XMLSchema\Type\BlockSetValue|null $block
     * @param \SimpleSAML\XMLSchema\Type\FormChoiceValue|null $form
     * @param \SimpleSAML\XMLSchema\XML\xs\Annotation|null $annotation
     * @param \SimpleSAML\XMLSchema\Type\Builtin\IDValue|null $id
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        ?NCNameValue $name = null,
        ?QNameValue $reference = null,
        LocalSimpleType|LocalComplexType|null $localType = null,
        array $identityConstraint = [],
        ?QNameValue $type = null,
        ?MinOccursValue $minOccurs = null,
        ?MaxOccursValue $maxOccurs = null,
        ?StringValue $default = null,
        ?StringValue $fixed = null,
        ?BooleanValue $nillable = null,
        ?BlockSetValue $block = null,
        ?FormChoiceValue $form = null,
        ?Annotation $annotation = null,
        ?IDValue $id = null,
        array $namespacedAttributes = [],
    ) {
        Assert::oneOf(strval($minOccurs), ['0', '1'], SchemaViolationException::class);
        Assert::oneOf(strval($maxOccurs), ['0', '1'], SchemaViolationException::class);

        parent::__construct(
            name: $name,
            reference: $reference,
            localType: $localType,
            identityConstraint: $identityConstraint,
            type: $type,
            minOccurs: $minOccurs,
            maxOccurs: $maxOccurs,
            default: $default,
            fixed: $fixed,
            nillable: $nillable,
            block: $block,
            form: $form,
            annotation: $annotation,
            id: $id,
            namespacedAttributes: $namespacedAttributes,
        );
    }
}
