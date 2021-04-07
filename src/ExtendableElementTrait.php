<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Constants;

/**
 * Trait grouping common functionality for elements implementing the xs:any element.
 *
 * @package simplesamlphp/xml-common
 */
trait ExtendableElementTrait
{
    /** @var \SimpleSAML\XML\XMLElementInterface[] */
    protected array $elements = [];

    /** @var string|array */
    protected $namespace = Constants::XS_ANY_NS_ANY;


    /**
     * Set an array with all elements present.
     *
     * @param array \SimpleSAML\XML\XMLElementInterface[]
     */
    public function setElements(array $elements): void
    {
        Assert::allIsInstanceOf($elements, XMLElementInterface::class);

        if (!is_array($this->namespace)) {
            Assert::oneOf($this->namespace, Constants::XS_ANY_NS);
        }

        // Get namespaces for all elements
        $actual_namespaces = array_map(
            function($elt) {
                return $elt->getNamespaceURI();
            },
            $elements
        );

        if ($this->namespace === Constants::XS_ANY_NS_LOCAL) {
            Assert::allNull($actual_namespaces);
        } elseif (is_array($this->namespace)) {
            // Make a local copy of the property that we can edit
            $allowed_namespaces = $this->namespace;

            // Replace the ##targetedNamespace with the actual namespace
            if (($key = array_search(Constants::XS_ANY_NS_TARGET, $allowed_namespaces)) !== false) {
                $allowed_namespaces[$key] = static::NS;
            }

            // Replace the ##local with the actual namespace
            if (($key = array_search(Constants::XS_ANY_NS_LOCAL, $allowed_namespaces)) !== false) {
                $allowed_namespaces[$key] = null;
            }

            $diff = array_diff($actual_namespaces, $allowed_namespaces);
            Assert::isEmpty($diff, 'Elements from namespaces [ ' . implode(', ', $diff) . '] are not allowed inside a ' . static::NS, ' element.');
        } else {
            Assert::allNotNull($actual_namespaces);

            if ($this->namespace === Constants::XS_ANY_NS_OTHER) {
                Assert::allNotSame($actual_namespaces, static::NS);
            } elseif ($this->namespace === Constants::XS_ANY_NS_TARGET) {
                Assert::allSame($actual_namespaces, static::NS);
            }
        }

        $this->elements = $elements;
    }


    /**
     * Get an array with all elements present.
     *
     * @return \SimpleSAML\XML\XMLElementInterface[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }


    /**
     * Set the value of the namespace-property
     *
     * @param string|array $namespace
     */
    public function setNamespace($namespace): void
    {
        Assert::true(is_array($namespace) || is_string($namespace));

        $this->namespace = $namespace;
    }


    /**
     * Test if an object, at the state it's in, would produce an empty XML-element
     *
     * @return bool
     */
    public function isEmptyElement(): bool
    {
        if (empty($this->elements)) {
            return true;
        }

        $empty = false;
        foreach ($this->elements as $elt) {
            $empty &= $elt->isEmptyElement();
        }

        return boolval($empty);
    }
}
