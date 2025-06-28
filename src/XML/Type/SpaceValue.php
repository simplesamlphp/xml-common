<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Type;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Attribute;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\XML\xml\SpaceEnum;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Builtin\NCNameValue;
use SimpleSAML\XMLSchema\Type\Helper\AttributeTypeInterface;

/**
 * @package simplesaml/xml-common
 */
class SpaceValue extends NCNameValue implements AttributeTypeInterface
{
    /**
     * Validate the value.
     *
     * @param string $value
     * @throws \SimpleSAML\XMLSchema\Exception\SchemaViolationException on failure
     * @return void
     */
    protected function validateValue(string $value): void
    {
        $sanitized = $this->sanitizeValue($value);
        parent::validateValue($sanitized);

        Assert::oneOf(
            $sanitized,
            [
                SpaceEnum::Default->value,
                SpaceEnum::Preserve->value,
            ],
            SchemaViolationException::class,
        );
    }


    /**
     * @param \SimpleSAML\XML\XML\xml\SpaceEnum $value
     * @return static
     */
    public static function fromEnum(SpaceEnum $value): static
    {
        return new static($value->value);
    }


    /**
     * @return \SimpleSAML\XML\XML\xml\SpaceEnum $value
     */
    public function toEnum(): SpaceEnum
    {
        return SpaceEnum::from($this->getValue());
    }


    /**
     * Convert this value to an attribute
     *
     * @return \SimpleSAML\XML\Attribute
     */
    public function toAttribute(): Attribute
    {
        return new Attribute(C::NS_XML, 'xml', 'space', $this);
    }
}
