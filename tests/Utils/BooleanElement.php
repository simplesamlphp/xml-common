<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\BooleanElementTrait;
use SimpleSAML\XML\SchemaValidatableElementInterface;
use SimpleSAML\XML\SchemaValidatableElementTrait;

/**
 * Empty shell class for testing BooleanElement.
 *
 * @package simplesaml/xml-common
 *
 * Note: this class is not final for testing purposes.
 */
class BooleanElement extends AbstractElement implements SchemaValidatableElementInterface
{
    use BooleanElementTrait;
    use SchemaValidatableElementTrait;

    /** @var string */
    public const NS = 'urn:x-simplesamlphp:namespace';

    /** @var string */
    public const NS_PREFIX = 'ssp';

    public const SCHEMA = '/file/does/not/exist.xsd';


    /**
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->setContent($content);
    }
}
