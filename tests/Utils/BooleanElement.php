<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\BooleanElementTrait;

/**
 * Empty shell class for testing BooleanElement.
 *
 * @package simplesaml/xml-common
 */
final class BooleanElement extends AbstractElement
{
    use BooleanElementTrait;

    /** @var string */
    public const NS = 'urn:x-simplesamlphp:namespace';

    /** @var string */
    public const NS_PREFIX = 'ssp';


    /**
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->setContent($content);
    }
}
