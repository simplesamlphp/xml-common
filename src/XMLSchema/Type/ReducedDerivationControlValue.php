<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Type;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\XML\xs\DerivationControlEnum;

use function array_column;

/**
 * @package simplesaml/xml-common
 */
class ReducedDerivationControlValue extends DerivationControlValue
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
            array_column(
                [
                    DerivationControlEnum::Extension,
                    DerivationControlEnum::Restriction,
                ],
                'value',
            ),
            SchemaViolationException::class,
        );
    }
}
