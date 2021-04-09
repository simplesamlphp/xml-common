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
     * @param array $validators  An array of callbacks that may perform validations on the content
     */
    public function __construct(string $content, array $validators = [])
    {
        $this->setContent($content, $validators);
    }


    /**
     * Set the content of the element.
     *
     * @param string $content  The value to go in the XML textContent
     * @param array $validators  An array of callbacks that may perform validations on the content
     */
    protected function setContent(string $content, $validators): void
    {
        if (!empty($validators)) {
            foreach ($validators as $validator) {
                call_user_func($validator, $content);
            }
        }

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
}
