<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\{SchemaValidatableElementInterface, SchemaValidatableElementTrait};
use SimpleSAML\XML\TypedTextContentTrait;

/**
 * Empty shell class for testing xs:base64Binary elements.
 *
 * @package simplesaml/xml-common
 */
final class Base64BinaryElement extends AbstractElement implements SchemaValidatableElementInterface
{
    use TypedTextContentTrait;
    use SchemaValidatableElementTrait;

    /** @var string */
    public const NS = 'urn:x-simplesamlphp:namespace';

    /** @var string */
    public const NS_PREFIX = 'ssp';

    /** @var string */
    public const SCHEMA = 'tests/resources/schemas/deliberately-wrong-file.xsd';

    /** @var string */
    public const TEXTCONTENT_TYPE = AbstractElement::class; // Deliberately wrong class


    /**
     * NOTE: This class has some deliberately wrong values for testing purposes!!
     */
}
