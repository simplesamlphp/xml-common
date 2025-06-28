<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Type;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Builtin\NMTokenValue;
use SimpleSAML\XMLSchema\XML\xs\ProcessContentsEnum;

use function array_column;

/**
 * @package simplesaml/xml-common
 */
class ProcessContentsValue extends NMTokenValue
{
    /**
     * Validate the value.
     *
     * @param string $value The value
     * @throws \SimpleSAML\XMLSchema\Exception\SchemaViolationException on failure
     * @return void
     */
    protected function validateValue(string $value): void
    {
        Assert::oneOf(
            $this->sanitizeValue($value),
            array_column(ProcessContentsEnum::cases(), 'value'),
            SchemaViolationException::class,
        );
    }


    /**
     * @param \SimpleSAML\XMLSchema\XML\xs\ProcessContentsEnum $value
     * @return static
     */
    public static function fromEnum(ProcessContentsEnum $value): static
    {
        return new static($value->value);
    }


    /**
     * @return \SimpleSAML\XMLSchema\XML\xs\ProcessContentsEnum $value
     */
    public function toEnum(): ProcessContentsEnum
    {
        return ProcessContentsEnum::from($this->getValue());
    }
}
