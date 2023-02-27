<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMDocument;
use DOMElement;
use RuntimeException;
use SimpleSAML\XML\Exception\MissingAttributeException;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\SerializableElementTrait;
use SimpleSAML\Assert\Assert;

use function array_slice;
use function defined;
use function explode;
use function func_num_args;
use function in_array;
use function intval;
use function join;

/**
 * Abstract class to be implemented by all classes
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractElement implements ElementInterface, SerializableElementInterface
{
    use SerializableElementTrait;


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
            $doc = new DOMDocument();
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
     *
     * @psalm-return ($default is string ? string : string|null)
     * @throws \SimpleSAML\Assert\AssertionFailedException if the attribute is missing from the element
     */
    public static function getAttribute(DOMElement $xml, string $name, ?string $default = null): ?string
    {
        if (!$xml->hasAttribute($name)) {
            $prefix = static::getNamespacePrefix();
            $localName = static::getLocalName();
            $qName = $prefix ? ($prefix . ':' . $localName) : $localName;
            Assert::true(
                func_num_args() === 3,
                sprintf('Missing \'%s\' attribute on %s.', $name, $qName),
                MissingAttributeException::class,
            );

            return $default;
        }

        return $xml->getAttribute($name);
    }


    /**
     * @param \DOMElement $xml The element where we should search for the attribute.
     * @param string      $name The name of the attribute.
     * @param bool|null   $default The default to return in case the attribute does not exist and it is optional.
     * @return bool|null
     *
     * @psalm-return ($default is bool ? bool : bool|null)
     * @throws \SimpleSAML\Assert\AssertionFailedException if the attribute is not a boolean
     */
    public static function getBooleanAttribute(DOMElement $xml, string $name, ?bool $default = null): ?bool
    {
        try {
            $value = self::getAttribute($xml, $name);
        } catch (MissingAttributeException $e) {
            if (func_num_args() === 3) {
                return $default;
            }
            throw $e;
        }

        $prefix = static::getNamespacePrefix();
        $localName = static::getLocalName();
        $qName = $prefix ? ($prefix . ':' . $localName) : $localName;
        Assert::oneOf(
            $value,
            ['0', '1', 'false', 'true'],
            sprintf('The \'%s\' attribute of %s must be a boolean.', $name, $qName),
        );

        return in_array($value, ['1', 'true'], true);
    }


    /**
     * Get the integer value of an attribute from a given element.
     *
     * @param \DOMElement $xml The element where we should search for the attribute.
     * @param string      $name The name of the attribute.
     * @param int|null $default The default to return in case the attribute does not exist and it is optional.
     * @return int|null
     *
     * @psalm-return ($default is int ? int : int|null)
     * @throws \SimpleSAML\Assert\AssertionFailedException if the attribute is not an integer
     */
    public static function getIntegerAttribute(DOMElement $xml, string $name, ?int $default = null): ?int
    {
        try {
            $value = self::getAttribute($xml, $name);
        } catch (MissingAttributeException $e) {
            if (func_num_args() === 3) {
                return $default;
            }
            throw $e;
        }

        $prefix = static::getNamespacePrefix();
        $localName = static::getLocalName();
        $qName = $prefix ? ($prefix . ':' . $localName) : $localName;
        Assert::numeric(
            $value,
            sprintf('The \'%s\' attribute of %s must be numerical.', $name, $qName),
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
        $ncName = join('', array_slice(explode('\\', $class), -1));
        Assert::validNCName($ncName, SchemaViolationException::class);
        return $ncName;
    }


    /**
     * Get the XML qualified name (prefix:name) of the element represented by this class.
     *
     * @return string
     */
    public function getQualifiedName(): string
    {
        $prefix = static::getNamespacePrefix();
        $qName = $prefix ? ($prefix . ':' . static::getLocalName()) : static::getLocalName();
        Assert::validQName($qName, SchemaViolationException::class);
        return $qName;
    }


    /**
     * Extract localized names from the children of a given element.
     *
     * @param \DOMElement $parent The element we want to search.
     * @return static[] An array of objects of this class.
     */
    public static function getChildrenOfClass(DOMElement $parent): array
    {
        $ret = [];
        foreach ($parent->childNodes as $node) {
            if (
                $node->namespaceURI === static::getNamespaceURI()
                && $node->localName === static::getLocalName()
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
     * @return string|null
     */
    public static function getNamespaceURI(): ?string
    {
        Assert::true(
            defined('static::NS'),
            self::getClassName(static::class)
            . '::NS constant must be defined and set to the namespace for the XML-class it represents.',
            RuntimeException::class,
        );
        Assert::nullOrValidURI(static::NS, SchemaViolationException::class);

        return static::NS;
    }


    /**
     * Get the namespace-prefix for the element.
     *
     * @return string|null
     */
    public static function getNamespacePrefix(): ?string
    {
        Assert::true(
            defined('static::NS_PREFIX'),
            self::getClassName(static::class)
            . '::NS_PREFIX constant must be defined and set to the namespace prefix for the XML-class it represents.',
            RuntimeException::class,
        );

        return static::NS_PREFIX;
    }


    /**
     * Get the local name for the element.
     *
     * @return string
     */
    public static function getLocalName(): string
    {
        if (defined('static::LOCALNAME')) {
            $ncName = static::LOCALNAME;
        } else {
            $ncName = self::getClassName(static::class);
        }

        Assert::validNCName($ncName, SchemaViolationException::class);
        return $ncName;
    }


    /**
     * Test if an object, at the state it's in, would produce an empty XML-element
     *
     * @codeCoverageIgnore
     * @return bool
     */
    public function isEmptyElement(): bool
    {
        return false;
    }
}
