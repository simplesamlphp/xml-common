<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML;

use Dom;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\ExtendableAttributesTrait;
use SimpleSAML\XML\SchemaValidatableElementInterface;
use SimpleSAML\XML\SchemaValidatableElementTrait;
use SimpleSAML\XML\Type\LangValue;
use SimpleSAML\XMLSchema\Exception\InvalidDOMElementException;
use SimpleSAML\XMLSchema\Type\AnyURIValue;
use SimpleSAML\XMLSchema\XML\Constants\NS;

use function strval;

/**
 * Class representing the documentation element
 *
 * @package simplesamlphp/xml-common
 */
final class Documentation extends AbstractXsElement implements SchemaValidatableElementInterface
{
    use ExtendableAttributesTrait;
    use SchemaValidatableElementTrait;


    public const string LOCALNAME = 'documentation';

    /** The namespace-attribute for the xs:anyAttribute element */
    public const string XS_ANY_ATTR_NAMESPACE = NS::OTHER;

    /** The exclusions for the xs:anyAttribute element */
    public const array XS_ANY_ATTR_EXCLUSIONS = [
        [C::NS_XML, 'lang'],
    ];


    /**
     * Documentation constructor
     *
     * @param \Dom\NodeList<\Dom\Node> $content
     * @param \SimpleSAML\XML\Type\LangValue|null $lang
     * @param \SimpleSAML\XMLSchema\Type\AnyURIValue|null $source
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    final public function __construct(
        protected Dom\NodeList $content,
        protected ?LangValue $lang = null,
        protected ?AnyURIValue $source = null,
        array $namespacedAttributes = [],
    ) {
        Assert::lessThanEq($content->count(), C::UNBOUNDED_LIMIT);
        $this->setAttributesNS($namespacedAttributes);
    }


    /**
     * Get the content property.
     *
     * @return \Dom\NodeList<\Dom\Node>
     */
    public function getContent(): Dom\NodeList
    {
        return $this->content;
    }


    /**
     * Get the lang property.
     *
     * @return \SimpleSAML\XML\Type\LangValue|null
     */
    public function getLang(): ?LangValue
    {
        return $this->lang;
    }


    /**
     * Get the source property.
     *
     * @return \SimpleSAML\XMLSchema\Type\AnyURIValue|null
     */
    public function getSource(): ?AnyURIValue
    {
        return $this->source;
    }


    /**
     * Test if an object, at the state it's in, would produce an empty XML-element
     *
     * @return bool
     */
    public function isEmptyElement(): bool
    {
        return $this->getContent()->count() === 0
            && empty($this->getLang())
            && empty($this->getSource())
            && empty($this->getAttributesNS());
    }


    /**
     * Create an instance of this object from its XML representation.
     *
     * @param \Dom\Element $xml
     * @return static
     *
     * @throws \SimpleSAML\XMLSchema\Exception\InvalidDOMElementException
     *   if the qualified name of the supplied element is wrong
     */
    public static function fromXML(Dom\Element $xml): static
    {
        Assert::same($xml->localName, static::getLocalName(), InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, static::NS, InvalidDOMElementException::class);

        $lang = $xml->hasAttributeNS(C::NS_XML, 'lang')
            ? LangValue::fromString($xml->getAttributeNS(C::NS_XML, 'lang'))
            : null;

        return new static(
            $xml->childNodes,
            $lang,
            self::getOptionalAttribute($xml, 'source', AnyURIValue::class, null),
            self::getAttributesNSFromXML($xml),
        );
    }


    /**
     * Add this Documentation to an XML element.
     *
     * @param \Dom\Element|null $parent The element we should append this Documentation to.
     * @return \Dom\Element
     */
    public function toXML(?Dom\Element $parent = null): Dom\Element
    {
        $e = parent::instantiateParentElement($parent);

        if ($this->getSource() !== null) {
            $e->setAttribute('source', strval($this->getSource()));
        }

        $this->getLang()?->toAttribute()->toXML($e);

        foreach ($this->getAttributesNS() as $attr) {
            $attr->toXML($e);
        }

        foreach ($this->getContent() as $i) {
            $e->appendChild($e->ownerDocument->importNode($i, true));
        }

        return $e;
    }
}
