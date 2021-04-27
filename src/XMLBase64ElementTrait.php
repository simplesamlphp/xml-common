<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Constants;

/**
 * Trait grouping common functionality for simple elements with base64 textContent
 *
 * @package simplesamlphp/xml-common
 */
trait XMLBase64ElementTrait
{
    use XMLStringElementTrait;


    /**
     * @param string $content
     */
    public function __construct(string $content)
    {
        // Check sanitized content against Base64 alphabet
        $sanitized = $this->sanitizeContent($content);
        $this->validateContent($sanitized);

        // Still keep the unsanitized content as a reference
        $this->setContent($content);
    }


    /**
     * Get the content of the element.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->sanitizeContent($this->getRawContent());
    }


    /**
     * Get the raw and unsanitized content of the element.
     *
     * @return string
     */
    public function getRawContent(): string
    {
        return $this->content;
    }


    /**
     * Sanitize the content of the element.
     *
     * @param string $content  The unsanitized textContent
     * @throws \Exception on failure
     * @return string
     */
    protected function sanitizeContent(string $content): string
    {
        return str_replace(["\r", "\n", "\t", ' '], '', $content);
    }


    /**
     * Validate the content of the element.
     *
     * @param string $content  The value to go in the XML textContent
     * @throws \Exception on failure
     * @return void
     */
    protected function validateContent(string $content): void
    {
        // Note: content must already be sanitized before validating
        Assert::stringPlausibleBase64($content);
    }
}
