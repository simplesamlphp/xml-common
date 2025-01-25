<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Type;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\Exception\SchemaViolationException;

/**
 * @package simplesaml/xml-common
 */
class EntitiesValue extends TokenValue implements ListTypeInterface
{
    /** @var string */
    public const SCHEMA_TYPE = 'xs:ENTITIES';


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
        Assert::validEntities($this->sanitizeValue($value), SchemaViolationException::class);
    }


    /**
     * Convert this xs:ENTITIES to an array of xs:ENTITY items
     *
     * @return array<\SimpleSAML\XML\Type\EntityValue>
     */
    public function toArray(): array
    {
        $tokens = explode(' ', $this->getValue(), C::UNBOUNDED_LIMIT);
        return array_map([EntityValue::class, 'fromString'], $tokens);
    }
}
