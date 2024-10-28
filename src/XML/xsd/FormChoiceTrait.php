<?php

declare(strict_types=1);

namespace SimpleSAML\XML\xsd;

/**
 * Trait grouping common functionality for elements that can hold a formChoice attribute.
 *
 * @package simplesamlphp/xml-common
 */
trait FormChoiceTrait
{
    /**
     * The formChoice.
     *
     * @var string
     */
    protected string $formChoice;


    /**
     * Collect the value of the formChoice-property
     *
     * @return string
     */
    public function getFormChoice(): string
    {
        return $this->formChoice;
    }


    /**
     * Set the value of the formChoice-property
     *
     * @param string $formChoice
     */
    protected function setFormChoice(string $formChoice): void
    {
        Assert::regex($formChoice, '/\c+/', SchemaViolationException::class);
        $this->formChoice = $formChoice;
    }
}
