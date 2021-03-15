<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMElement;
use RuntimeException;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\Exception\MissingAttributeException;
use Serializable;
use SimpleSAML\Assert\Assert;

/**
 * Abstract class to be implemented by all classes
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractXMLElement extends AbstractSerializableXML
{
    /**
     * Create a document structure for this element
     *
     * @param \DOMElement|null $parent The element we should append to.
     * @return \DOMElement
     */
    public function instantiateParentElement(DOMElement $parent = null): DOMElement
    {
        $qualifiedName = $this->getQualifiedName();
        $namespace = static::getNamespaceURI();

        if ($parent === null) {
            $doc = DOMDocumentFactory::create();
            $e = $doc->createElementNS($namespace, $qualifiedName);
            $doc->appendChild($e);
        } else {
            $doc = $parent->ownerDocument;
            Assert::notNull($doc);

            /** @psalm-var \DOMDocument $doc */
            $e = $doc->createElementNS($namespace, $qualifiedName);
            $parent->appendChild($e);
        }

        return $e;
    }


    /**
     * Get the value of an attribute from a given element.
     *
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
                'Missing \'' . $name . '\' attribute on ' . static::getNamespacePrefix() . ':'
                    . self::getClassName(static::class) . '.',
                MissingAttributeException::class
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
            'The \'' . $name . '\' attribute of ' . static::getNamespacePrefix() . ':' . self::getClassName(static::class) .
            ' must be boolean.'
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
            'The \'' . $name . '\' attribute of ' . static::getNamespacePrefix() . ':' . self::getClassName(static::class)
                . ' must be numerical.'
        );

        return intval($value);
    }


    /**
     * Static method that processes a fully namespaced class name and returns the name of the class from it.
     *
     * @param string $class
     * @return string
     */
    public static function getClassName(string $class): string
    {
        return join('', array_slice(explode('\\', $class), -1));
    }


    /**
     * Get the XML local name of the element represented by this class.
     *
     * @return string
     */
    public function getLocalName(): string
    {
        return self::getClassName(get_class($this));
    }


    /**
     * Get the XML qualified name (prefix:name) of the element represented by this class.
     *
     * @return string
     */
    public function getQualifiedName(): string
    {
        return static::getNamespacePrefix() . ':' . $this->getLocalName();
    }


    /**
     * Extract localized names from the children of a given element.
     *
     * @param \DOMElement $parent The element we want to search.
     * @return static[] An array of objects of this class.
     * @psalm-return array
     */
    public static function getChildrenOfClass(DOMElement $parent): array
    {
        $ret = [];
        foreach ($parent->childNodes as $node) {
            if (
                $node->namespaceURI === static::getNamespaceURI()
                && $node->localName === self::getClassName(static::class)
            ) {
                /** @psalm-var \DOMElement $node */
                $ret[] = static::fromXML($node);
            }
        }
        return $ret;
    }


    /**
     * Get the namespace for the element.
     *
     * @return string
     */
    abstract public static function getNamespaceURI(): string;


    /**
     * Get the namespace-prefix for the element.
     *
     * @return string
     */
    abstract public static function getNamespacePrefix(): string;


    /**
     * Create a class from an array
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): object
    {
        throw new RuntimeException('Not implemented.');
    }


    /**
     * Create an array from this class
     *
     * @return array
     */
    public function toArray(): array
    {
        throw new RuntimeException('Not implemented');
    }
}
