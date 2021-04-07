<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMElement;
use SimpleSAML\Assert\Assert;

/**
 * Trait grouping common functionality for elements implementing the xs:any element.
 *
 * @package simplesamlphp/xml-common
 */
trait ExtendableElementTrait
{
    /** @var \SimpleSAML\XML\XMLElementInterface[] */
    protected array $extensions = [];


    /**
     * Extensions constructor.
     *
     * @var \SimpleSAML\XML\XMLElementInterface[]
     */
    public function __construct(array $extensions)
    {
        $this->setList($extensions);
    }


    /**
     * Set an array with all extensions present.
     *
     * @param array \SimpleSAML\XML\XMLElementInterface[]
     */
    public function setList(array $extensions): void
    {
        Assert::allIsInstanceOf($extensions, XMLElementInterface::class);

        $this->extensions = $extensions;
    }


    /**
     * Get an array with all extensions present.
     *
     * @return \SimpleSAML\XML\XMLElementInterface[]
     */
    public function getList(): array
    {
        return $this->extensions;
    }


    /**
     * @return bool
     */
    public function isEmptyElement(): bool
    {
        if (empty($this->extensions)) {
            return true;
        }

        $empty = false;
        foreach ($this->extensions as $extension) {
            $empty &= $extension->isEmptyElement();
        }

        return boolval($empty);
    }


    /**
     * Convert this object into its md:Extensions XML representation.
     *
     * @param \DOMElement|null $parent The element we should add this Extensions element to.
     * @return \DOMElement The new md:Extensions XML element.
     */
    public function toXML(DOMElement $parent = null): DOMElement
    {
        $e = $this->instantiateParentElement($parent);

        foreach ($this->extensions as $extension) {
            if (!$extension->isEmptyElement()) {
                $extension->toXML($e);
            }
        }

        return $e;
    }


    /**
     * @param \DOMElement|null $parent
     * @return \DOMElement
     */
    abstract public function instantiateParentElement(DOMElement $parent = null): DOMElement;
}