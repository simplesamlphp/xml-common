<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Type\Schema;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\XML\Enumeration\DerivationControlEnum;

use function array_column;
use function explode;

/**
 * @package simplesaml/xml-common
 */
class FullDerivationSetValue extends DerivationControlValue
{
    /** @var string */
    public const SCHEMA_TYPE = 'fullDerivationSet';


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

        if ($sanitized !== '#all' && $sanitized !== '') {
            Assert::allOneOf(
                explode(' ', $sanitized),
                array_column(
                    [
                        DerivationControlEnum::Extension,
                        DerivationControlEnum::List,
                        DerivationControlEnum::Restriction,
                        DerivationControlEnum::Union,
                    ],
                    'value',
                ),
                SchemaViolationException::class,
            );
        }
    }
}
