<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMElement;
use RuntimeException;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\Constants as C;

use function array_diff;
use function array_map;
use function array_search;
use function defined;
use function implode;
use function is_array;
use function rtrim;
use function sprintf;

/**
 * Trait grouping common functionality for elements implementing the xs:any element.
 *
 * @package simplesamlphp/xml-common
 */
trait ExtendableElementTrait
{
    /** @var \SimpleSAML\XML\ElementInterface[] */
    protected array $elements = [];


    /**
     * Set an array with all elements present.
     *
     * @param \SimpleSAML\XML\ElementInterface[] $elements
     * @return void
     */
    protected function setElements(array $elements): void
    {
        Assert::maxCount($elements, C::UNBOUNDED_LIMIT);
        Assert::allIsInstanceOf($elements, ElementInterface::class);
        $namespace = $this->getElementNamespace();

        // Validate namespace value
        if (!is_array($namespace)) {
            // Must be one of the predefined values
            Assert::oneOf($namespace, C::XS_ANY_NS);
        } else {
            // Array must be non-empty and cannot contain ##any or ##other
            Assert::notEmpty($namespace);
            Assert::allNotSame($namespace, C::XS_ANY_NS_ANY);
            Assert::allNotSame($namespace, C::XS_ANY_NS_OTHER);
        }

        // Get namespaces for all elements
        $actual_namespaces = array_map(
            /**
             * @param \SimpleSAML\XML\ElementInterface $elt
             * @return string|null
             */
            function (ElementInterface $elt) {
                /** @psalm-var \SimpleSAML\XML\Chunk|\SimpleSAML\XML\AbstractElement $elt */
                return ($elt instanceof Chunk) ? $elt->getNamespaceURI() : $elt::getNamespaceURI();
            },
            $elements
        );

        if ($namespace === C::XS_ANY_NS_LOCAL) {
            // If ##local then all namespaces must be null
            Assert::allNull($actual_namespaces);
        } elseif (is_array($namespace)) {
            // Make a local copy of the property that we can edit
            $allowed_namespaces = $namespace;

            // Replace the ##targetedNamespace with the actual namespace
            if (($key = array_search(C::XS_ANY_NS_TARGET, $allowed_namespaces)) !== false) {
                $allowed_namespaces[$key] = static::NS;
            }

            // Replace the ##local with null
            if (($key = array_search(C::XS_ANY_NS_LOCAL, $allowed_namespaces)) !== false) {
                $allowed_namespaces[$key] = null;
            }

            $diff = array_diff($actual_namespaces, $allowed_namespaces);
            Assert::isEmpty(
                $diff,
                sprintf(
                    'Elements from namespaces [ %s ] are not allowed inside a %s element.',
                    rtrim(implode(', ', $diff)),
                    static::NS,
                ),
            );
        } elseif ($namespace === C::XS_ANY_NS_OTHER) {
            // Must be any namespace other than the parent element, excluding elements with no namespace
            Assert::notInArray(null, $actual_namespaces);
            Assert::allNotSame($actual_namespaces, static::NS);
        } elseif ($namespace === C::XS_ANY_NS_TARGET) {
            // Must be the same namespace as the one of the parent element
            Assert::allSame($actual_namespaces, static::NS);
        } else {
            // XS_ANY_NS_ANY
        }

        $this->elements = $elements;
    }


    /**
     * Get an array with all elements present.
     *
     * @return \SimpleSAML\XML\ElementInterface[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }


    /**
     * @return array|string
     */
    public function getElementNamespace(): array|string
    {
        Assert::true(
            defined('static::XS_ANY_ELT_NAMESPACE'),
            self::getClassName(static::class)
            . '::XS_ANY_ELT_NAMESPACE constant must be defined and set to the namespace for the xs:any element.',
            RuntimeException::class,
        );

        return static::XS_ANY_ELT_NAMESPACE;
    }
}
