<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

/**
 * Trait grouping common functionality for elements that are part of the xs:typeDefParticle group.
 *
 * @package simplesamlphp/xml-common
 */
trait TypeDefParticleTrait
{
    /**
     * The particle.
     *
     * @var \SimpleSAML\XMLSchema\XML\xs\TypeDefParticleInterface|null
     */
    protected ?TypeDefParticleInterface $particle = null;


    /**
     * Collect the value of the particle-property
     *
     * @return \SimpleSAML\XMLSchema\XML\xs\TypeDefParticleInterface|null
     */
    public function getParticle(): ?TypeDefParticleInterface
    {
        return $this->particle;
    }


    /**
     * Set the value of the particle-property
     *
     * @param \SimpleSAML\XMLSchema\XML\xs\TypeDefParticleInterface|null $particle
     */
    protected function setParticle(?TypeDefParticleInterface $particle = null): void
    {
        $this->particle = $particle;
    }
}
