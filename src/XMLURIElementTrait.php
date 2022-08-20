<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Constants;
use SimpleSAML\XML\Exception\SchemaViolationException;

use function str_replace;

/**
 * Trait grouping common functionality for simple elements with xs:anyURI textContent
 *
 * @package simplesamlphp/xml-common
 */
trait XMLURIElementTrait
{
    use XMLStringElementTrait;


    /**
     * Validate the content of the element.
     *
     * @param string $content  The value to go in the XML textContent
     * @throws \Exception on failure
     * @return void
     */
    protected function validateContent(string $content): void
    {
        Assert::validURI($content, SchemaViolationException::class);
    }
}
