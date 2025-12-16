<?php

declare(strict_types=1);

namespace SimpleSAML\Test\Helper;

use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\SchemaValidatableElementInterface;
use SimpleSAML\XML\SchemaValidatableElementTrait;
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


    public const string NS = 'urn:x-simplesamlphp:namespace';

    public const string NS_PREFIX = 'ssp';

    public const string SCHEMA = 'tests/resources/schemas/deliberately-wrong-file.xsd';

    public const string TEXTCONTENT_TYPE = AbstractElement::class; // Deliberately wrong class


    /**
     * NOTE: This class has some deliberately wrong values for testing purposes!!
     */
}
