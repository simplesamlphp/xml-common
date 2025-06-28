<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use SimpleSAML\XML\{SchemaValidatableElementInterface, SchemaValidatableElementTrait};

/**
 * Class representing the pattern element
 *
 * @package simplesamlphp/xml-common
 */
final class Pattern extends AbstractNoFixedFacet implements SchemaValidatableElementInterface, FacetInterface
{
    use SchemaValidatableElementTrait;

    /** @var string */
    public const LOCALNAME = 'pattern';
}
