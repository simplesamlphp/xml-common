<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMElement;
use SimpleSAML\XML\Exception\MissingAttributeException;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Utils;
use SimpleSAML\Assert\Assert;

use function in_array;
use function intval;

/**
 * Serializable class used to hold an XML element.
 *
 * @package simplesamlphp/xml-common
 */
final class Chunk implements ElementInterface, SerializableElementInterface
{
    /**
     * Whether to format the string output of this element or not.
     *
     * Defaults to true. Override to disable output formatting.
     *
     * @var bool
     */
    protected bool $formatOutput = true;

    /**
     * The prefix of this element.
     *
     * @var string|null
     */
    protected ?string $prefix;

    /**
     * The \DOMElement we contain.
     *
     * @var \DOMElement
     */
    protected DOMElement $xml;


    /**
     * Create an XML Chunk from a copy of the given \DOMElement.
     *
     * @param \DOMElement $xml The element we should copy.
     */
    public function __construct(DOMElement $xml)
    {
        $this->setLocalName($xml->localName);
        $this->setNamespaceURI($xml->namespaceURI);
        $this->setPrefix($xml->prefix);

        $this->xml = Utils::copyElement($xml);
    }


    /**
     * Output the class as an XML-formatted string
     *
     * @return string
     */
    public function __toString(): string
    {

        $xml = $this->toXML();

        /** @psalm-var \DOMDocument $xml->ownerDocument */
        $xml->ownerDocument->formatOutput = $this->formatOutput;
        return $xml->ownerDocument->saveXML($xml);
    }


    /**
     * Serialize this XML chunk.
     *
     * This method will be invoked by any calls to serialize().
     *
     * @return array The serialized representation of this XML object.
     */
    public function __serialize(): array
    {
        $xml = $this->toXML();
        /** @psalm-var \DOMDocument $xml->ownerDocument */
        return [$xml->ownerDocument->saveXML($xml)];
    }


    /**
     * Unserialize an XML object and load it..
     *
     * This method will be invoked by any calls to unserialize(), allowing us to restore any data that might not
     * be serializable in its original form (e.g.: DOM objects).
     *
     * @param array $serialized The XML object that we want to restore.
     */
    public function __unserialize(array $serialized): void
    {
        $xml = static::fromXML(
            DOMDocumentFactory::fromString(array_pop($serialized))->documentElement,
        );

        $vars = get_object_vars($xml);
        foreach ($vars as $k => $v) {
            $this->$k = $v;
        }
    }


    /**
     * Get this \DOMElement.
     *
     * @return \DOMElement This element.
     */
    public function getXML(): DOMElement
    {
        return $this->xml;
    }


    /**
     * Collect the value of the localName-property
     *
     * @return string
     */
    public function getLocalName(): string
    {
        return $this->localName;
    }


    /**
     * Set the value of the localName-property
     *
     * @param string $localName
     * @throws \SimpleSAML\Assert\AssertionFailedException if $localName is an empty string
     */
    public function setLocalName(string $localName): void
    {
        Assert::validNCName($localName, SchemaViolationException::class); // Covers the empty string
        $this->localName = $localName;
    }


    /**
     * Collect the value of the namespaceURI-property
     *
     * @return string|null
     */
    public function getNamespaceURI(): ?string
    {
        return $this->namespaceURI;
    }


    /**
     * Set the value of the namespaceURI-property
     *
     * @param string|null $namespaceURI
     */
    protected function setNamespaceURI(string $namespaceURI = null): void
    {
        Assert::nullOrValidURI($namespaceURI, SchemaViolationException::class);
        $this->namespaceURI = $namespaceURI;
    }


    /**
     * Collect the value of the prefix-property
     *
     * @return string|null
     */
    public function getPrefix(): ?string
    {
        return $this->prefix;
    }


    /**
     * Set the value of the prefix-property
     *
     * @param string|null $prefix
     */
    protected function setPrefix(string $prefix = null): void
    {
        $this->prefix = $prefix;
    }


    /**
     * Get the XML qualified name (prefix:name, or just name when not prefixed)
     *  of the element represented by this class.
     *
     * @return string
     */
    public function getQualifiedName(): string
    {
        $prefix = $this->getPrefix();

        if (is_null($prefix)) {
            return $this->getLocalName();
        } else {
            return $prefix . ':' . $this->getLocalName();
        }
    }


    /*
     * @param \DOMElement $xml The element where we should search for the attribute.
     * @param string      $name The name of the attribute.
     * @param string|null $default The default to return in case the attribute does not exist and it is optional.
     * @return string|null
     * @throws \SimpleSAML\Assert\AssertionFailedException if the attribute is missing from the element
     */
    public static function getAttribute(DOMElement $xml, string $name, ?string $default = ''): ?string
    {
        if (!$xml->hasAttribute($name)) {
            Assert::nullOrStringNotEmpty(
                $default,
                'Missing \'' . $name . '\' attribute on ' . $xml->prefix . ':' . $xml->localName . '.',
                MissingAttributeException::class,
            );

            return $default;
        }

        return $xml->getAttribute($name);
    }


    /**
     * @param \DOMElement $xml The element where we should search for the attribute.
     * @param string      $name The name of the attribute.
     * @param string|null $default The default to return in case the attribute does not exist and it is optional.
     * @return bool|null
     * @throws \SimpleSAML\Assert\AssertionFailedException if the attribute is not a boolean
     */
    public static function getBooleanAttribute(DOMElement $xml, string $name, ?string $default = ''): ?bool
    {
        $value = self::getAttribute($xml, $name, $default);
        if ($value === null) {
            return null;
        }

        Assert::oneOf(
            $value,
            ['0', '1', 'false', 'true'],
            'The \'' . $name . '\' attribute of ' . $xml->prefix . ':' . $xml->localName . ' must be boolean.',
        );

        return in_array($value, ['1', 'true'], true);
    }


    /**
     * Get the integer value of an attribute from a given element.
     *
     * @param \DOMElement $xml The element where we should search for the attribute.
     * @param string      $name The name of the attribute.
     * @param string|null $default The default to return in case the attribute does not exist and it is optional.
     *
     * @return int|null
     * @throws \SimpleSAML\Assert\AssertionFailedException if the attribute is not an integer
     */
    public static function getIntegerAttribute(DOMElement $xml, string $name, ?string $default = ''): ?int
    {
        $value = self::getAttribute($xml, $name, $default);
        if ($value === null) {
            return null;
        }

        Assert::numeric(
            $value,
            'The \'' . $name . '\' attribute of ' . $xml->prefix . ':' . $xml->localName . ' must be numerical.',
        );

        return intval($value);
    }


    /**
     * Test if an object, at the state it's in, would produce an empty XML-element
     *
     * @return bool
     */
    public function isEmptyElement(): bool
    {
        $xml = $this->getXML();
        return (
            ($xml->childNodes->length === 0)
            && ($xml->attributes->length === 0)
        );
    }


    /**
     * @param \DOMElement $xml
     * @return self
     */
    public static function fromXML(DOMElement $xml): self
    {
        return new self($xml);
    }


    /**
     * Append this XML element to a different XML element.
     *
     * @param  \DOMElement|null $parent The element we should append this element to.
     * @return \DOMElement The new element.
     */
    public function toXML(DOMElement $parent = null): DOMElement
    {
        return Utils::copyElement($this->xml, $parent);
    }
}
