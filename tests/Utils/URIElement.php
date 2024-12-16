<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\URIElementTrait;
use SimpleSAML\XML\SchemaValidatableElementInterface;
use SimpleSAML\XML\SchemaValidatableElementTrait;

/**
 * Empty shell class for testing URIElement.
 *
 * @package simplesaml/xml-common
 */
final class URIElement extends AbstractElement implements SchemaValidatableElementInterface
{
    use SchemaValidatableElementTrait;
    use URIElementTrait;

    /** @var string */
    public const NS = 'urn:x-simplesamlphp:namespace';

    /** @var string */
    public const NS_PREFIX = 'ssp';

    /** @var string */
    public const SCHEMA = 'tests/resources/schemas/simplesamlphp.xsd';


    /**
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->setContent($content);
    }
}
