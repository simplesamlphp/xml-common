<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Type;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\Exception\SchemaViolationException;

/**
 * @package simplesaml/xml-common
 */
class IDRefsValue extends TokenValue implements ListTypeInterface
{
    /** @var string */
    public const SCHEMA_TYPE = 'IDREFS';


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
        Assert::validIDRefs($this->sanitizeValue($value), SchemaViolationException::class);
    }


    /**
     * Convert this xs:IDREFS to an array of xs:IDREF items
     *
     * @return array<\SimpleSAML\XML\Type\IDRefValue>
     */
    public function toArray(): array
    {
        $tokens = explode(' ', $this->getValue(), C::UNBOUNDED_LIMIT);
        return array_map([IDRefValue::class, 'fromString'], $tokens);
    }
}
