<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

use SimpleSAML\XML\{SchemaValidatableElementInterface, SchemaValidatableElementTrait};

/**
 * Class representing the maxLength element
 *
 * @package simplesamlphp/xml-common
 */
final class MaxLength extends AbstractNumFacet implements SchemaValidatableElementInterface, FacetInterface
{
    use SchemaValidatableElementTrait;

    /** @var string */
    public const LOCALNAME = 'maxLength';
}
