<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMElement;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Exception\{InvalidDOMElementException, InvalidValueTypeException};
use SimpleSAML\XML\Type\{ValueTypeInterface, StringValue};

use function defined;
use function strval;

/**
 * Trait for elements that hold a typed textContent value.
 *
 * @package simplesaml/xml-common
 */
trait TypedTextContentTrait
{
    /**
     * @param \SimpleSAML\XML\Type\ValueTypeInterface $content
     */
    public function __construct(
        protected ValueTypeInterface $content,
    ) {
    }


    /**
     * Create a class from XML
     *
     * @param \DOMElement $xml
     * @return static
     */
    public static function fromXML(DOMElement $xml): static
    {
        Assert::same($xml->localName, static::getLocalName(), InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, static::NS, InvalidDOMElementException::class);

        $type = self::getTextContentType();
        $text = $type::fromString($xml->textContent);

        return new static($text);
    }


    /**
     * Get the typed content of the element
     *
     * @return \SimpleSAML\XML\Type\ValueTypeInterface
     */
    public function getContent(): ValueTypeInterface
    {
        return $this->content;
    }


    /**
     * Create XML from this class
     *
     * @param \DOMElement|null $parent
     * @return \DOMElement
     */
    public function toXML(?DOMElement $parent = null): DOMElement
    {
        $e = $this->instantiateParentElement($parent);
        $e->textContent = strval($this->getContent());

        return $e;
    }


    /**
     * Get the type the element's textContent value.
     *
     * @return class-string
     */
    public static function getTextContentType(): string
    {
        if (defined('static::TEXTCONTENT_TYPE')) {
            $type = static::TEXTCONTENT_TYPE;
        } else {
            $type = StringValue::class;
        }

        Assert::isAOf($type, ValueTypeInterface::class, InvalidValueTypeException::class);
        return $type;
    }
}
