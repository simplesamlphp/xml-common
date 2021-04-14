<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Constants;

/**
 * Trait grouping common functionality for simple elements with just some textContent
 *
 * @package simplesamlphp/xml-common
 */
trait XMLStringElementTrait
{
    /** @var string */
    protected string $content;


    /**
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->setContent($content);
    }


    /**
     * Set the content of the element.
     *
     * @param string $content  The value to go in the XML textContent
     */
    protected function setContent(string $content): void
    {
        $this->validateContent($content);
        $this->content = $content;
    }


    /**
     * Get the content of the element.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }


    /**
     * Validate the content of the element.
     *
     * @param string $content  The value to go in the XML textContent
     * @throws \Exception on failure
     * @return void
     */
    private function validateContent(string $content): void
    {
        /**
         * Perform no validation by default.
         * Override this method on the implementing class to perform content validation.
         */
    }


    /**
     * Convert this element to XML.
     *
     * @param \DOMElement|null $parent The element we should append this element to.
     * @return \DOMElement
     */
    public function toXML(DOMElement $parent = null): DOMElement
    {
        $e = $this->instantiateParentElement($parent);
        $e->textContent = $this->content;

        return $e;
    }
}
