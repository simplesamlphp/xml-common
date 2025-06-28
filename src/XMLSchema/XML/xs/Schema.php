<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use DOMElement;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Attribute as XMLAttribute;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\{SchemaValidatableElementInterface, SchemaValidatableElementTrait};
use SimpleSAML\XML\Utils\XPath;
use SimpleSAML\XMLSchema\Exception\{InvalidDOMElementException, SchemaViolationException};
use SimpleSAML\XMLSchema\Type\Builtin\{AnyURIValue, IDValue, StringValue, TokenValue};
use SimpleSAML\XMLSchema\Type\{BlockSetValue, FormChoiceValue, FullDerivationSetValue};

use function array_merge;
use function strval;

/**
 * Class representing the schema-element
 *
 * @package simplesamlphp/xml-common
 */
final class Schema extends AbstractOpenAttrs implements SchemaValidatableElementInterface
{
    use SchemaValidatableElementTrait;

    /** @var string */
    public const LOCALNAME = 'schema';

    /** The exclusions for the xs:anyAttribute element */
    public const XS_ANY_ATTR_EXCLUSIONS = [
        [C::NS_XML, 'lang'],
    ];


    /**
     * Schema constructor
     *
     * @param (
     *     \SimpleSAML\XMLSchema\XML\xs\XsInclude|
     *     \SimpleSAML\XMLSchema\XML\xs\Import|
     *     \SimpleSAML\XMLSchema\XML\xs\Redefine|
     *     \SimpleSAML\XMLSchema\XML\xs\Annotation
     * )[] $topLevelElements
     * @param (
     *     \SimpleSAML\XMLSchema\XML\xs\TopLevelSimpleType|
     *     \SimpleSAML\XMLSchema\XML\xs\TopLevelComplexType|
     *     \SimpleSAML\XMLSchema\XML\xs\NamedGroup|
     *     \SimpleSAML\XMLSchema\XML\xs\NamedAttributeGroup|
     *     \SimpleSAML\XMLSchema\XML\xs\TopLevelAttribute|
     *     \SimpleSAML\XMLSchema\XML\xs\TopLevelElement|
     *     \SimpleSAML\XMLSchema\XML\xs\Notation|
     *     \SimpleSAML\XMLSchema\XML\xs\Annotation
     * )[] $schemaTopElements
     * @param \SimpleSAML\XMLSchema\Type\Builtin\AnyURIValue $targetNamespace
     * @param \SimpleSAML\XMLSchema\Type\Builtin\TokenValue $version
     * @param \SimpleSAML\XMLSchema\Type\FullDerivationSetValue $finalDefault
     * @param \SimpleSAML\XMLSchema\Type\BlockSetValue $blockDefault
     * @param \SimpleSAML\XMLSchema\Type\FormChoiceValue|null $attributeFormDefault
     * @param \SimpleSAML\XMLSchema\Type\FormChoiceValue|null $elementFormDefault
     * @param \SimpleSAML\XMLSchema\Type\Builtin\IDValue|null $id
     * @param \SimpleSAML\XML\Attribute|null $lang
     * @param array<\SimpleSAML\XML\Attribute> $namespacedAttributes
     */
    public function __construct(
        protected array $topLevelElements = [],
        protected array $schemaTopElements = [],
        protected ?AnyURIValue $targetNamespace = null,
        protected ?TokenValue $version = null,
        protected ?FullDerivationSetValue $finalDefault = null,
        protected ?BlockSetValue $blockDefault = null,
        protected ?FormChoiceValue $attributeFormDefault = null,
        protected ?FormChoiceValue $elementFormDefault = null,
        protected ?IDValue $id = null,
        protected ?XMLAttribute $lang = null,
        array $namespacedAttributes = [],
    ) {
        Assert::allIsInstanceOfAny(
            $topLevelElements,
            [XsInclude::class, Import::class, Redefine::class, Annotation::class],
            SchemaViolationException::class,
        );
        Assert::allIsInstanceOfAny(
            $schemaTopElements,
            [
                RedefinableInterface::class,
                TopLevelAttribute::class,
                TopLevelElement::class,
                Notation::class,
                Annotation::class,
            ],
            SchemaViolationException::class,
        );

        parent::__construct($namespacedAttributes);
    }


    /**
     * Collect the value of the topLevelElements-property
     *
     * @return (
     *     \SimpleSAML\XMLSchema\XML\xs\XsInclude|
     *     \SimpleSAML\XMLSchema\XML\xs\Import|
     *     \SimpleSAML\XMLSchema\XML\xs\Redefine|
     *     \SimpleSAML\XMLSchema\XML\xs\Annotation
     * )[]
     */
    public function getTopLevelElements(): array
    {
        return $this->topLevelElements;
    }


    /**
     * Collect the value of the schemaTopElements-property
     *
     * @return (
     *     \SimpleSAML\XMLSchema\XML\xs\RedefinableInterface|
     *     \SimpleSAML\XMLSchema\XML\xs\TopLevelAttribute|
     *     \SimpleSAML\XMLSchema\XML\xs\TopLevelElement|
     *     \SimpleSAML\XMLSchema\XML\xs\Notation|
     *     \SimpleSAML\XMLSchema\XML\xs\Annotation
     * )[]
     */
    public function getSchemaTopElements(): array
    {
        return $this->schemaTopElements;
    }


    /**
     * Collect the value of the targetNamespace-property
     *
     * @return \SimpleSAML\XMLSchema\Type\Builtin\AnyURIValue|null
     */
    public function getTargetNamespace(): ?AnyURIValue
    {
        return $this->targetNamespace;
    }


    /**
     * Collect the value of the version-property
     *
     * @return \SimpleSAML\XMLSchema\Type\Builtin\TokenValue|null
     */
    public function getVersion(): ?TokenValue
    {
        return $this->version;
    }


    /**
     * Collect the value of the blockDefault-property
     *
     * @return \SimpleSAML\XMLSchema\Type\BlockSetValue|null
     */
    public function getBlockDefault(): ?BlockSetValue
    {
        return $this->blockDefault;
    }


    /**
     * Collect the value of the finalDefault-property
     *
     * @return \SimpleSAML\XMLSchema\Type\FullDerivationSetValue|null
     */
    public function getFinalDefault(): ?FullDerivationSetValue
    {
        return $this->finalDefault;
    }


    /**
     * Collect the value of the attributeFormDefault-property
     *
     * @return \SimpleSAML\XMLSchema\Type\FormChoiceValue|null
     */
    public function getAttributeFormDefault(): ?FormChoiceValue
    {
        return $this->attributeFormDefault;
    }


    /**
     * Collect the value of the elementFormDefault-property
     *
     * @return \SimpleSAML\XMLSchema\Type\FormChoiceValue|null
     */
    public function getElementFormDefault(): ?FormChoiceValue
    {
        return $this->elementFormDefault;
    }


    /**
     * Collect the value of the id-property
     *
     * @return \SimpleSAML\XMLSchema\Type\Builtin\IDValue|null
     */
    public function getID(): ?IDValue
    {
        return $this->id;
    }


    /**
     * Collect the value of the lang-property
     *
     * @return \SimpleSAML\XML\Attribute|null
     */
    public function getLang(): ?XMLAttribute
    {
        return $this->lang;
    }


    /**
     * Test if an object, at the state it's in, would produce an empty XML-element
     *
     * @return bool
     */
    public function isEmptyElement(): bool
    {
        return parent::isEmptyElement() &&
            empty($this->getTopLevelElements()) &&
            empty($this->getSchemaTopElements()) &&
            empty($this->getTargetNamespace()) &&
            empty($this->getVersion()) &&
            empty($this->getFinalDefault()) &&
            empty($this->getBlockDefault()) &&
            empty($this->getAttributeFormDefault()) &&
            empty($this->getElementFormDefault()) &&
            empty($this->getId()) &&
            empty($this->getLang());
    }


    /**
     * Create an instance of this object from its XML representation.
     *
     * @param \DOMElement $xml
     * @return static
     *
     * @throws \SimpleSAML\XMLSchema\Exception\InvalidDOMElementException
     *   if the qualified name of the supplied element is wrong
     */
    public static function fromXML(DOMElement $xml): static
    {
        Assert::same($xml->localName, static::getLocalName(), InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, static::NS, InvalidDOMElementException::class);

        $xpCache = XPath::getXPath($xml);

        $beforeAllowed = [
            'annotation' => Annotation::class,
            'import' => Import::class,
            'include' => XsInclude::class,
            'redefine' => Redefine::class,
        ];
        $beforeSchemaTopElements = XPath::xpQuery(
            $xml,
            '('
            . '/xs:schema/xs:group|'
            . '/xs:schema/xs:attributeGroup|'
            . '/xs:schema/xs:complexType|'
            . '/xs:schema/xs:simpleType|'
            . '/xs:schema/xs:element|'
            .  '/xs:schema/xs:attribute'
            . ')[1]/preceding-sibling::xs:*',
            $xpCache,
        );

        $topLevelElements = [];
        foreach ($beforeSchemaTopElements as $node) {
            /** @var \DOMElement $node */
            if ($node instanceof DOMElement) {
                if ($node->namespaceURI === C::NS_XS && array_key_exists($node->localName, $beforeAllowed)) {
                    $topLevelElements[] = $beforeAllowed[$node->localName]::fromXML($node);
                }
            }
        }

        $afterAllowed = [
            'annotation' => Annotation::class,
            'attribute' => TopLevelAttribute::class,
            'attributeGroup' => NamedAttributeGroup::class,
            'complexType' => TopLevelComplexType::class,
            'element' => TopLevelElement::class,
            'notation' => Notation::class,
            'simpleType' => TopLevelSimpleType::class,
        ];
        $afterSchemaTopElementFirstHit = XPath::xpQuery(
            $xml,
            '('
            . '/xs:schema/xs:group|'
            . '/xs:schema/xs:attributeGroup|'
            . '/xs:schema/xs:complexType'
            . '/xs:schema/xs:simpleType|'
            . '/xs:schema/xs:element|'
            . '/xs:schema/xs:attribute'
            . ')[1]',
            $xpCache,
        );

        $afterSchemaTopElementSibling = XPath::xpQuery(
            $xml,
            '('
            . '/xs:schema/xs:group|'
            . '/xs:schema/xs:attributeGroup|'
            . '/xs:schema/xs:complexType'
            . '/xs:schema/xs:simpleType|'
            . '/xs:schema/xs:element|'
            . '/xs:schema/xs:attribute'
            . ')[1]/following-sibling::xs:*',
            $xpCache,
        );

        $afterSchemaTopElements = array_merge($afterSchemaTopElementFirstHit, $afterSchemaTopElementSibling);

        $schemaTopElements = [];
        foreach ($afterSchemaTopElements as $node) {
            /** @var \DOMElement $node */
            if ($node instanceof DOMElement) {
                if ($node->namespaceURI === C::NS_XS && array_key_exists($node->localName, $afterAllowed)) {
                    $schemaTopElements[] = $afterAllowed[$node->localName]::fromXML($node);
                }
            }
        }

        $lang = null;
        if ($xml->hasAttributeNS(C::NS_XML, 'lang')) {
            $lang = new XMLAttribute(
                C::NS_XML,
                'xml',
                'lang',
                StringValue::fromString($xml->getAttributeNS(C::NS_XML, 'lang')),
            );
        }

        return new static(
            $topLevelElements,
            $schemaTopElements,
            self::getOptionalAttribute($xml, 'targetNamespace', AnyURIValue::class, null),
            self::getOptionalAttribute($xml, 'version', TokenValue::class, null),
            self::getOptionalAttribute($xml, 'finalDefault', FullDerivationSetValue::class, null),
            self::getOptionalAttribute($xml, 'blockDefault', BlockSetValue::class, null),
            self::getOptionalAttribute($xml, 'attributeFormDefault', FormChoiceValue::class, null),
            self::getOptionalAttribute($xml, 'elementFormDefault', FormChoiceValue::class, null),
            self::getOptionalAttribute($xml, 'id', IDValue::class, null),
            $lang,
            self::getAttributesNSFromXML($xml),
        );
    }


    /**
     * Add this Schema to an XML element.
     *
     * @param \DOMElement|null $parent The element we should append this Schema to.
     * @return \DOMElement
     */
    public function toXML(?DOMElement $parent = null): DOMElement
    {
        $e = parent::toXML($parent);

        if ($this->getTargetNamespace() !== null) {
            $e->setAttribute('targetNamespace', strval($this->getTargetNamespace()));
        }

        if ($this->getVersion() !== null) {
            $e->setAttribute('version', strval($this->getVersion()));
        }

        if ($this->getFinalDefault() !== null) {
            $e->setAttribute('finalDefault', strval($this->getFinalDefault()));
        }

        if ($this->getBlockDefault() !== null) {
            $e->setAttribute('blockDefault', strval($this->getBlockDefault()));
        }

        if ($this->getAttributeFormDefault() !== null) {
            $e->setAttribute('attributeFormDefault', strval($this->getAttributeFormDefault()));
        }

        if ($this->getElementFormDefault() !== null) {
            $e->setAttribute('elementFormDefault', strval($this->getElementFormDefault()));
        }

        if ($this->getId() !== null) {
            $e->setAttribute('id', strval($this->getId()));
        }

        if ($this->getLang() !== null) {
            $this->getLang()->toXML($e);
        }

        foreach ($this->getTopLevelElements() as $tle) {
            $tle->toXML($e);
        }

        foreach ($this->getSchemaTopElements() as $ste) {
            $ste->toXML($e);
        }

        return $e;
    }
}
