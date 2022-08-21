<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\Exception\InvalidDOMElementException;

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
     * @var bool
     */
    protected ?bool $boolean;

    /**
     * @var int
     */
    protected ?int $integer;

    /**
     * @var string
     */
    protected ?string $text;


    /**
     * @param int|null $integer
     * @param bool|null $boolean
     * @param string|null $text
     */
    public function __construct(?int $integer = null, ?bool $boolean = null, ?string $text = null)
    {
        $this->integer = $integer;
        $this->boolean = $boolean;
        $this->text = $text;
    }


    /**
     * Collect the value of the integer-property
     *
     * @return int|null
     */
    public function getInteger(): ?int
    {
        return $this->integer;
    }


    /**
     * Collect the value of the boolean-property
     *
     * @return bool|null
     */
    public function getBoolean(): ?bool
    {
        return $this->boolean;
    }


    /**
     * Collect the value of the text-property
     *
     * @return string|null
     */
    public function getString(): ?string
    {
        return $this->text;
    }


    /**
     * Create a class from XML
     *
     * @param \DOMElement $xml
     * @return self
     */
    public static function fromXML(DOMElement $xml): self
    {
        Assert::same($xml->localName, static::getLocalName(), InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, static::NS, InvalidDOMElementException::class);

        $integer = self::getIntegerAttribute($xml, 'integer');
        $boolean = self::getBooleanAttribute($xml, 'boolean');
        $text = self::getAttribute($xml, 'text');

        return new static($integer, $boolean, $text);
    }


    /**
     * Create XML from this class
     *
     * @param \DOMElement|null $parent
     * @return \DOMElement
     */
    public function toXML(DOMElement $parent = null): DOMElement
    {
        $e = $this->instantiateParentElement($parent);

        if ($this->integer !== null) {
            $e->setAttribute('integer', strval($this->integer));
        }

        if ($this->boolean !== null) {
            $e->setAttribute('boolean', $this->boolean ? 'true' : 'false');
        }

        if ($this->text !== null) {
            $e->setAttribute('text', $this->text);
        }

        return $e;
    }
}
