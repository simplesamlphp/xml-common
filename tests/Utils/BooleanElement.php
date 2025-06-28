<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\{SchemaValidatableElementInterface, SchemaValidatableElementTrait};
use SimpleSAML\XML\TypedTextContentTrait;
use SimpleSAML\XMLSchema\Type\Builtin\BooleanValue;

/**
 * Empty shell class for testing xs:string elements.
 *
 * @package simplesaml/xml-common
 */
final class BooleanElement extends AbstractElement implements SchemaValidatableElementInterface
{
    use SchemaValidatableElementTrait;
    use TypedTextContentTrait;

    /** @var string */
    public const NS = 'urn:x-simplesamlphp:namespace';

    /** @var string */
    public const NS_PREFIX = 'ssp';

    /** @var string */
    public const SCHEMA = 'tests/resources/schemas/simplesamlphp.xsd';

    /** @var string */
    public const TEXTCONTENT_TYPE = BooleanValue::class;
}
