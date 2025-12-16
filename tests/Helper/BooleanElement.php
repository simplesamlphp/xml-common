<?php

declare(strict_types=1);

namespace SimpleSAML\Test\Helper;

use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\SchemaValidatableElementInterface;
use SimpleSAML\XML\SchemaValidatableElementTrait;
use SimpleSAML\XML\TypedTextContentTrait;
use SimpleSAML\XMLSchema\Type\BooleanValue;

/**
 * Empty shell class for testing xs:string elements.
 *
 * @package simplesaml/xml-common
 */
final class BooleanElement extends AbstractElement implements SchemaValidatableElementInterface
{
    use SchemaValidatableElementTrait;
    use TypedTextContentTrait;


    public const string NS = 'urn:x-simplesamlphp:namespace';

    public const string NS_PREFIX = 'ssp';

    public const string SCHEMA = 'tests/resources/schemas/simplesamlphp.xsd';

    public const string TEXTCONTENT_TYPE = BooleanValue::class;
}
