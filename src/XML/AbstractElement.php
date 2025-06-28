<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMElement;
use RuntimeException;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\SerializableElementTrait;
use SimpleSAML\XMLSchema\Exception\{MissingAttributeException, SchemaViolationException};
use SimpleSAML\XMLSchema\Type\Builtin\{QNameValue, StringValue};
use SimpleSAML\XMLSchema\Type\Helper\ValueTypeInterface;

use function array_slice;
use function defined;
use function explode;
use function join;
use function strval;

/**
 * Abstract class to be implemented by all classes
 *
 * @package simplesamlphp/xml-common
 */
abstract class AbstractElement implements SerializableElementInterface
{
    use SerializableElementTrait;


    /**
     * Create a document structure for this element
     *
     * @param \DOMElement|null $parent The element we should append to.
     * @return \DOMElement
     */
    public function instantiateParentElement(?DOMElement $parent = null): DOMElement
    {
        $qualifiedName = $this->getQualifiedName();
        $namespace = static::getNamespaceURI();

        if ($parent === null) {
            $parent = DOMDocumentFactory::create();
            $e = $parent->createElementNS($namespace, $qualifiedName);
        } else {
            $doc = $parent->ownerDocument;
            Assert::notNull($doc);
            $e = $doc->createElementNS($namespace, $qualifiedName);
        }

        $parent->appendChild($e);

        return $e;
    }


    /**
     * @template T of \SimpleSAML\XMLSchema\Type\Helper\ValueTypeInterface
     * @param \DOMElement     $xml The element where we should search for the attribute.
     * @param string          $name The name of the attribute.
     * @param class-string<T> $type The type of the attribute value.
     * @return T
     *
     * @throws \SimpleSAML\XMLSchema\Exception\MissingAttributeException if the attribute is missing from the element
     */
    public static function getAttribute(
        DOMElement $xml,
        string $name,
        string $type = StringValue::class,
    ): ValueTypeInterface {
        Assert::isAOf($type, ValueTypeInterface::class);

        $prefix = static::getNamespacePrefix();
        $localName = static::getLocalName();
        $qName = $prefix ? ($prefix . ':' . $localName) : $localName;
        Assert::true(
            $xml->hasAttribute($name),
            sprintf('Missing \'%s\' attribute on %s.', $name, $qName),
            MissingAttributeException::class,
        );

        $value = $xml->getAttribute($name);
        return ($type === QNameValue::class) ? QNameValue::fromDocument($value, $xml) : $type::fromString($value);
    }


    /**
     * Get the value of an attribute from a given element.
     *
     * @template T of \SimpleSAML\XMLSchema\Type\Helper\ValueTypeInterface
     * @param \DOMElement  $xml The element where we should search for the attribute.
     * @param string       $name The name of the attribute.
     * @param class-string<T> $type The type of the attribute value.
     * @param \SimpleSAML\XMLSchema\Type\Helper\ValueTypeInterface|null $default
     *   The default to return in case the attribute does not exist and it is optional.
     * @return ($default is \SimpleSAML\XMLSchema\Type\Helper\ValueTypeInterface ? T : T|null)
     */
    public static function getOptionalAttribute(
        DOMElement $xml,
        string $name,
        string $type = StringValue::class,
        ?ValueTypeInterface $default = null,
    ): ?ValueTypeInterface {
        Assert::nullOrIsInstanceOf($default, $type);

        if (!$xml->hasAttribute($name)) {
            return $default;
        }

        return self::getAttribute($xml, $name, $type);
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
     * @return array<static> An array of objects of this class.
     */
    public static function getChildrenOfClass(DOMElement $parent): array
    {
        $ret = [];
        foreach ($parent->childNodes as $node) {
            if (
                $node instanceof DOMElement
                && $node->namespaceURI === static::getNamespaceURI()
                && $node->localName === static::getLocalName()
            ) {
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
        // @phpstan-ignore classConstant.notFound
        Assert::nullOrValidURI(static::NS, SchemaViolationException::class); // @phpstan-ignore-line

        // @phpstan-ignore classConstant.notFound
        return static::NS; // @phpstan-ignore-line
    }


    /**
     * Get the namespace-prefix for the element.
     *
     * @return string
     */
    public static function getNamespacePrefix(): string
    {
        Assert::true(
            defined('static::NS_PREFIX'),
            self::getClassName(static::class)
            . '::NS_PREFIX constant must be defined and set to the namespace prefix for the XML-class it represents.',
            RuntimeException::class,
        );

        // @phpstan-ignore classConstant.notFound
        return strval(static::NS_PREFIX); // @phpstan-ignore-line
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
