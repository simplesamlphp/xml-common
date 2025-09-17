<?php

declare(strict_types=1);

namespace SimpleSAML\Test\Helper;

use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\SchemaValidatableElementInterface;
use SimpleSAML\XML\SchemaValidatableElementTrait;
use SimpleSAML\XML\TypedTextContentTrait;
use SimpleSAML\XMLSchema\Type\StringValue;

/**
 * Empty shell class for testing String elements.
 *
 * @package simplesaml/xml-common
 */
final class StringElement extends AbstractElement implements SchemaValidatableElementInterface
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
    public const TEXTCONTENT_TYPE = StringValue::class;
}
