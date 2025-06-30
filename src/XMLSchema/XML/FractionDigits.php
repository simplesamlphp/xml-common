<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML;

use SimpleSAML\XML\{SchemaValidatableElementInterface, SchemaValidatableElementTrait};
use SimpleSAML\XMLSchema\XML\Interface\FacetInterface;

/**
 * Class representing the fractionDigits element
 *
 * @package simplesamlphp/xml-common
 */
final class FractionDigits extends AbstractNumFacet implements SchemaValidatableElementInterface, FacetInterface
{
    use SchemaValidatableElementTrait;

    /** @var string */
    public const LOCALNAME = 'fractionDigits';
}
