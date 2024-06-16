<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use RuntimeException;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\XsNamespace as NS;

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
    /** @var \SimpleSAML\XML\SerializableElementInterface[] */
    protected array $elements = [];


    /**
     * Set an array with all elements present.
     *
     * @param \SimpleSAML\XML\SerializableElementInterface[] $elements
     * @return void
     */
    protected function setElements(array $elements): void
    {
        Assert::maxCount($elements, C::UNBOUNDED_LIMIT);
        Assert::allIsInstanceOf($elements, SerializableElementInterface::class);
        $namespace = $this->getElementNamespace();

        // Validate namespace value
        if (!is_array($namespace)) {
            // Must be one of the predefined values
            Assert::oneOf($namespace, NS::cases());
        } else {
            // Array must be non-empty and cannot contain ##any or ##other
            Assert::notEmpty($namespace);
            Assert::allNotSame($namespace, NS::ANY);
            Assert::allNotSame($namespace, NS::OTHER);
        }

        // Get namespaces for all elements
        $actual_namespaces = array_map(
            /**
             * @param \SimpleSAML\XML\SerializableElementInterface $elt
             * @return string|null
             */
            function (SerializableElementInterface $elt) {
                return ($elt instanceof Chunk) ? $elt->getNamespaceURI() : $elt::getNamespaceURI();
            },
            $elements,
        );

        if ($namespace === NS::LOCAL) {
            // If ##local then all namespaces must be null
            Assert::allNull($actual_namespaces);
        } elseif (is_array($namespace)) {
            // Make a local copy of the property that we can edit
            $allowed_namespaces = $namespace;

            // Replace the ##targetedNamespace with the actual namespace
            if (($key = array_search(NS::TARGET, $allowed_namespaces)) !== false) {
                $allowed_namespaces[$key] = static::NS;
            }

            // Replace the ##local with null
            if (($key = array_search(NS::LOCAL, $allowed_namespaces)) !== false) {
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
        } elseif ($namespace === NS::OTHER) {
            // Must be any namespace other than the parent element, excluding elements with no namespace
            Assert::notInArray(null, $actual_namespaces);
            Assert::allNotSame($actual_namespaces, static::NS);
        } elseif ($namespace === NS::TARGET) {
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
     * @return \SimpleSAML\XML\SerializableElementInterface[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }


    /**
     * @return array|\SimpleSAML\XML\XsNamespace
     */
    public function getElementNamespace(): array|NS
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
