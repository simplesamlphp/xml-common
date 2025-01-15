<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\Exception\InvalidDOMElementException;
use SimpleSAML\XML\Type\{BooleanValue, IntegerValue, StringValue, ValueTypeInterface};

use function strval;

/**
 * Empty shell class for testing AbstractElement.
 *
 * @package simplesaml/xml-common
 */
final class Element extends AbstractElement
{
    /** @var string */
    public const NS = 'urn:x-simplesamlphp:namespace';

    /** @var string */
    public const NS_PREFIX = 'ssp';


    /**
     * @param \SimpleSAML\XML\Type\IntegerValue $integer
     * @param \SimpleSAML\XML\Type\BooleanValue $boolean
     * @param \SimpleSAML\XML\Type\StringValue $text
     * @param \SimpleSAML\XML\Type\StringValue $otherText
     */
    public function __construct(
        protected IntegerValue $integer,
        protected BooleanValue $boolean,
        protected StringValue $text,
        protected StringValue $otherText,
    ) {
    }


    /**
     * Collect the value of the integer-property
     *
     * @return \SimpleSAML\XML\Type\IntegerValue
     */
    public function getInteger(): IntegerValue
    {
        return $this->integer;
    }


    /**
     * Collect the value of the boolean-property
     *
     * @return \SimpleSAML\XML\Type\BooleanValue
     */
    public function getBoolean(): BooleanValue
    {
        return $this->boolean;
    }


    /**
     * Collect the value of the text-property
     *
     * @return \SimpleSAML\XML\Type\StringValue
     */
    public function getString(): StringValue
    {
        return $this->text;
    }


    /**
     * Collect the value of the otherText-property
     *
     * @return \SimpleSAML\XML\Type\StringValue
     */
    public function getOtherString(): StringValue
    {
        return $this->otherText;
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

        $integer = self::getAttribute($xml, 'integer', IntegerValue::class);
        $boolean = self::getAttribute($xml, 'boolean', BooleanValue::class);
        $text = self::getAttribute($xml, 'text', StringValue::class);
        $otherText = self::getAttribute($xml, 'otherText');

        return new static($integer, $boolean, $text, $otherText);
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

        $e->setAttribute('integer', strval($this->getInteger()));
        $e->setAttribute('boolean', strval($this->getBoolean()));
        $e->setAttribute('text', strval($this->getString()));
        $e->setAttribute('otherText', strval($this->getOtherString()));

        return $e;
    }
}
