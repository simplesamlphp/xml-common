<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Type\Helper;

use DOMNodeList;
use DOMXPath;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XMLSchema\Type\StringValue;

/**
 * @package simplesaml/xml-common
 */
class XPathValue extends StringValue
{
    /**
     * Validate the content of the element.
     *
     * @throws \SimpleSAML\Assert\AssertionFailedException on failure
     */
    protected function validateValue(string $content): void
    {
        $dom = new DOMXPath(DOMDocumentFactory::create());

        $result = $dom->evaluate($content);
        Assert::isInstanceOf($result, DOMNodeList::class);
    }
}
