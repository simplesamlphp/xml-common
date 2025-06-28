<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;

/**
 * Trait grouping common functionality for elements that are part of the xs:facets group.
 *
 * @package simplesamlphp/xml-common
 */
trait FacetsTrait
{
    /**
     * The facets.
     *
     * @var \SimpleSAML\XMLSchema\XML\xs\FacetInterface[]
     */
    protected array $facets;


    /**
     * Collect the value of the facets-property
     *
     * @return \SimpleSAML\XMLSchema\XML\xs\FacetInterface[]
     */
    public function getFacets(): array
    {
        return $this->facets;
    }


    /**
     * Set the value of the facets-property
     *
     * @param \SimpleSAML\XMLSchema\XML\xs\FacetInterface[] $facets
     */
    protected function setFacets(array $facets): void
    {
        Assert::maxCount($facets, C::UNBOUNDED_LIMIT);
        Assert::allIsInstanceOf($facets, FacetInterface::class, SchemaViolationException::class);
        $this->facets = $facets;
    }
}
