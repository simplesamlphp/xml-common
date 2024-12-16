<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMDocument;

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
     * @return void
     * @throws \SimpleSAML\XML\Exception\SchemaViolationException
     */
    public static function schemaValidate(DOMDocument $document): DOMDocument;
}
