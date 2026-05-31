<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use Dom;

/**
 * interface class to be implemented by all the classes that can be validated against a schema
 *
 * @package simplesamlphp/xml-common
 */
interface SchemaValidatableElementInterface extends ElementInterface
{
    /**
     * Validate the given DOMDocument against the schema set for this element
     *
     * @param \Dom\Document $document
     * @param string|null $schemaFile
     *
     * @throws \SimpleSAML\XML\Exception\IOException
     * @throws \SimpleSAML\XMLSchema\Exception\SchemaViolationException
     */
    public static function schemaValidate(Dom\Document $document, ?string $schemaFile = null): Dom\Document;
}
