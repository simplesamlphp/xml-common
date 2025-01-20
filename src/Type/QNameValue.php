<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Type;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Exception\SchemaViolationException;

use function explode;

/**
 * @package simplesaml/xml-common
 */
class QNameValue extends AbstractValueType
{
    /**
     * Sanitize the value.
     *
     * @param string $value  The unsanitized value
     * @return string
     */
    protected function sanitizeValue(string $value): string
    {
        return static::collapseWhitespace(static::normalizeWhitespace($value));
    }


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
        Assert::validQName($this->sanitizeValue($value), SchemaViolationException::class);
    }


    /**
     * Get the namespace-prefix for this qualified name.
     *
     * @return \SimpleSAML\XML\Type\NCNameValue|null
     */
    public function getNamespacePrefix(): ?NCNameValue
    {
        $qname = explode(':', $this->getValue(), 2);
        if (count($qname) === 2) {
            return NCNameValue::fromString($qname[0]);
        }

        return null;
    }


    /**
     * Get the local name for this qualified name.
     *
     * @return \SimpleSAML\XML\Type\NCNameValue
     */
    public function getLocalName(): NCNameValue
    {
        $qname = explode(':', $this->getValue(), 2);
        if (count($qname) === 2) {
            return NCNameValue::fromString($qname[1]);
        }

        return NCNameValue::fromString($qname[0]);
    }
}
