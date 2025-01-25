<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Type;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\Exception\SchemaViolationException;

use function array_map;
use function explode;

/**
 * @package simplesaml/xml-common
 */
class NMTokensValue extends TokenValue implements ListTypeInterface
{
    /** @var string */
    public const SCHEMA_TYPE = 'xs:NMTOKENS';


    /**
     * Validate the value.
     *
     * @param string $value
     * @throws \SimpleSAML\XML\Exception\SchemaViolationException on failure
     * @return void
     */
    protected function validateValue(string $value): void
    {
        // Note: value must already be sanitized before validating
        Assert::validNMTokens($this->sanitizeValue($value), SchemaViolationException::class);
    }


    /**
     * Convert this xs:NMTokens to an array of xs:NMToken items
     *
     * @return array<\SimpleSAML\XML\Type\NMTokenValue>
     */
    public function toArray(): array
    {
        $tokens = explode(' ', $this->getValue(), C::UNBOUNDED_LIMIT);
        return array_map([NMTokenValue::class, 'fromString'], $tokens);
    }
}
