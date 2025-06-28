<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Type\Builtin;

use DOMElement;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Helper\AbstractAnySimpleType;

use function preg_match;

/**
 * @package simplesaml/xml-common
 */
class QNameValue extends AbstractAnySimpleType
{
    /** @var string */
    public const SCHEMA_TYPE = 'QName';

    /** @var \SimpleSAML\XMLSchema\Type\Builtin\AnyURIValue|null */
    protected ?AnyURIValue $namespaceURI;

    /** @var \SimpleSAML\XMLSchema\Type\Builtin\NCNameValue|null */
    protected ?NCNameValue $namespacePrefix;

    /** @var \SimpleSAML\XMLSchema\Type\Builtin\NCNameValue */
    protected NCNameValue $localName;

    /** @var string */
    private static string $qname_regex = '/^
        (?:
          \{                 # Match a literal {
            (\S+)            # Match one or more non-whitespace character
          \}                 # Match a literal }
          (?:
            ([\w_][\w.-]*)   # Match a-z or underscore followed by any word-character, dot or dash
            :                # Match a literal :
          )?
        )?                   # Namespace and prefix are optional
        ([\w_][\w.-]*)       # Match a-z or underscore followed by any word-character, dot or dash
        $/Dimx';


    /**
     * Sanitize the value.
     *
     * @param string $value  The unsanitized value
     * @return string
     */
    protected function sanitizeValue(string $value): string
    {
        return static::collapseWhitespace(static::normalizeWhitespace($value));
    }


    /**
     * Validate the value.
     *
     * @param string $value
     * @throws \SimpleSAML\XMLSchema\Exception\SchemaViolationException on failure
     * @return void
     */
    protected function validateValue(string $value): void
    {
        $qName = $this->sanitizeValue($value);

        /**
         * Split our custom format of {<namespaceURI>}<prefix>:<localName> into individual parts
         */
        $result = preg_match(
            self::$qname_regex,
            $qName,
            $matches,
            PREG_UNMATCHED_AS_NULL,
        );

        if ($result && count($matches) === 4) {
            list($qName, $namespaceURI, $namespacePrefix, $localName) = $matches;

            $this->namespaceURI = ($namespaceURI !== null) ? AnyURIValue::fromString($namespaceURI) : null;
            $this->namespacePrefix = ($namespacePrefix !== null) ? NCNameValue::fromString($namespacePrefix) : null;
            $this->localName = NCNameValue::fromString($localName);
        } else {
            throw new SchemaViolationException(sprintf('\'%s\' is not a valid xs:QName.', $qName));
        }
    }


    /**
     * Get the value.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->getNamespacePrefix() . ':' . $this->getLocalName();
    }


    /**
     * Get the namespaceURI for this qualified name.
     *
     * @return \SimpleSAML\XMLSchema\Type\Builtin\AnyURIValue|null
     */
    public function getNamespaceURI(): ?AnyURIValue
    {
        return $this->namespaceURI;
    }


    /**
     * Get the namespace-prefix for this qualified name.
     *
     * @return \SimpleSAML\XMLSchema\Type\Builtin\NCNameValue|null
     */
    public function getNamespacePrefix(): ?NCNameValue
    {
        return $this->namespacePrefix;
    }


    /**
     * Get the local name for this qualified name.
     *
     * @return \SimpleSAML\XMLSchema\Type\Builtin\NCNameValue
     */
    public function getLocalName(): NCNameValue
    {
        return $this->localName;
    }


    /**
     * @param \SimpleSAML\XMLSchema\Type\Builtin\NCNameValue $localName
     * @param \SimpleSAML\XMLSchema\Type\Builtin\AnyURIValue|null $namespaceURI
     * @param \SimpleSAML\XMLSchema\Type\Builtin\NCNameValue|null $namespacePrefix
     * @return static
     */
    public static function fromParts(
        NCNameValue $localName,
        ?AnyURIValue $namespaceURI,
        ?NCNameValue $namespacePrefix,
    ): static {
        if ($namespaceURI === null) {
            // If we don't have a namespace, we can't have a prefix either
            Assert::null($namespacePrefix, SchemaViolationException::class);
            return new static($localName->getValue());
        }

        return new static(
            '{' . $namespaceURI->getValue() . '}'
            . ($namespacePrefix ? ($namespacePrefix->getValue() . ':') : '')
            . $localName,
        );
    }


    /**
     * @param string $qName
     */
    public static function fromDocument(
        string $qName,
        DOMElement $element,
    ): static {
        $namespacePrefix = null;
        if (str_contains($qName, ':')) {
            list($namespacePrefix, $localName) = explode(':', $qName, 2);
        } else {
            // No prefix
            $localName = $qName;
        }

        // Will return the default namespace (if any) when prefix is NULL
        $namespaceURI = $element->lookupNamespaceUri($namespacePrefix);

        return new static('{' . $namespaceURI . '}' . ($namespacePrefix ? $namespacePrefix . ':' : '') . $localName);
    }
}
