<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

/**
 * Trait grouping common functionality for elements that can occur in the xs:simpleDerivation group.
 *
 * @package simplesamlphp/xml-common
 */
trait SimpleDerivationTrait
{
    /**
     * The derivation.
     *
     * @var \SimpleSAML\XMLSchema\XML\xs\SimpleDerivationInterface
     */
    protected SimpleDerivationInterface $derivation;


    /**
     * Collect the value of the derivation-property
     *
     * @return \SimpleSAML\XMLSchema\XML\xs\SimpleDerivationInterface
     */
    public function getDerivation(): SimpleDerivationInterface
    {
        return $this->derivation;
    }


    /**
     * Set the value of the derivation-property
     *
     * @param \SimpleSAML\XMLSchema\XML\xs\SimpleDerivationInterface $derivation
     */
    protected function setDerivation(SimpleDerivationInterface $derivation): void
    {
        $this->derivation = $derivation;
    }
}
