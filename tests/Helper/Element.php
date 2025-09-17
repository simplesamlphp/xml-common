<?php

declare(strict_types=1);

namespace SimpleSAML\Test\Helper;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XMLSchema\Exception\InvalidDOMElementException;
use SimpleSAML\XMLSchema\Type\BooleanValue;
use SimpleSAML\XMLSchema\Type\IntegerValue;
use SimpleSAML\XMLSchema\Type\StringValue;

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
     * @param \SimpleSAML\XMLSchema\Type\IntegerValue|null $integer
     * @param \SimpleSAML\XMLSchema\Type\BooleanValue|null $boolean
     * @param \SimpleSAML\XMLSchema\Type\StringValue|null $text
     * @param \SimpleSAML\XMLSchema\Type\StringValue|null $otherText
     */
    public function __construct(
        protected ?IntegerValue $integer = null,
        protected ?BooleanValue $boolean = null,
        protected ?StringValue $text = null,
        protected ?StringValue $otherText = null,
    ) {
    }


    /**
     * Collect the value of the integer-property
     *
     * @return \SimpleSAML\XMLSchema\Type\IntegerValue|null
     */
    public function getInteger(): ?IntegerValue
    {
        return $this->integer;
    }


    /**
     * Collect the value of the boolean-property
     *
     * @return \SimpleSAML\XMLSchema\Type\BooleanValue|null
     */
    public function getBoolean(): ?BooleanValue
    {
        return $this->boolean;
    }


    /**
     * Collect the value of the text-property
     *
     * @return \SimpleSAML\XMLSchema\Type\StringValue|null
     */
    public function getString(): ?StringValue
    {
        return $this->text;
    }


    /**
     * Collect the value of the otherText-property
     *
     * @return \SimpleSAML\XMLSchema\Type\StringValue|null
     */
    public function getOtherString(): ?StringValue
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

        if ($this->getInteger() !== null) {
            $e->setAttribute('integer', strval($this->getInteger()));
        }

        if ($this->getBoolean() !== null) {
            $e->setAttribute('boolean', strval($this->getBoolean()));
        }

        if ($this->getString() !== null) {
            $e->setAttribute('text', strval($this->getString()));
        }

        if ($this->getOtherString() !== null) {
            $e->setAttribute('otherText', strval($this->getOtherString()));
        }

        return $e;
    }
}
