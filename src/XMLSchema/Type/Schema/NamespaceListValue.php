<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Type\Schema;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\NMTokensValue;
use SimpleSAML\XMLSchema\XML\Enumeration\NamespaceEnum;

use function explode;

/**
 * @package simplesaml/xml-common
 */
class NamespaceListValue extends NMTokensValue
{
    /** @var string */
    public const SCHEMA_TYPE = 'namespaceList';


    /**
     * Validate the value.
     *
     * @param string $value The value
     * @throws \SimpleSAML\XMLSchema\Exception\SchemaViolationException on failure
     * @return void
     */
    protected function validateValue(string $value): void
    {
        $sanitized = $this->sanitizeValue($value);

        if ($sanitized !== NamespaceEnum::Any->value && $sanitized !== NamespaceEnum::Other->value) {
            $list = explode(' ', $sanitized, C::UNBOUNDED_LIMIT);

            // After filtering the two special namespaces, only AnyURI's should be left
            $filtered = array_diff(
                $list,
                [
                    NamespaceEnum::TargetNamespace->value,
                    NamespaceEnum::Local->value,
                ],
            );
            Assert::false(
                in_array(NamespaceEnum::Any->value, $filtered) || in_array(NamespaceEnum::Other->value, $filtered),
                SchemaViolationException::class,
            );
            Assert::notEmpty($sanitized, SchemaViolationException::class);
            Assert::allValidAnyURI($filtered, SchemaViolationException::class);
        }
    }


    /**
     * @param \SimpleSAML\XMLSchema\XML\Enumeration\NamespaceEnum $value
     * @return static
     */
    public static function fromEnum(NamespaceEnum $value): static
    {
        return new static($value->value);
    }


    /**
     * @return \SimpleSAML\XMLSchema\XML\Enumeration\NamespaceEnum $value
     */
    public function toEnum(): NamespaceEnum
    {
        return NamespaceEnum::from($this->getValue());
    }
}
