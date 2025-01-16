<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use DOMElement;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Exception\InvalidDOMElementException;
use SimpleSAML\XML\{SchemaValidatableElementInterface, SchemaValidatableElementTrait};
use SimpleSAML\XML\Type\StringValue;

use function strval;

/**
 * Empty shell class for testing String elements.
 *
 * @package simplesaml/xml-common
 */
final class StringElement extends AbstractElement implements SchemaValidatableElementInterface
{
    use SchemaValidatableElementTrait;

    /** @var string */
    public const NS = 'urn:x-simplesamlphp:namespace';

    /** @var string */
    public const NS_PREFIX = 'ssp';

    /** @var string */
    public const SCHEMA = 'tests/resources/schemas/simplesamlphp.xsd';


    /**
     * @param \SimpleSAML\XML\Type\StringValue $content
     */
    public function __construct(
        protected StringValue $content,
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

        $text = StringValue::fromString($xml->textContent);

        return new static($text);
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
        $e->textContent = strval($this->content);

        return $e;
    }
}
