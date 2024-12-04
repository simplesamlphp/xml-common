<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\HexBinaryElementTrait;

/**
 * Empty shell class for testing HexBinaryElement.
 *
 * @package simplesaml/xml-common
 */
final class HexBinaryElement extends AbstractElement
{
    use HexBinaryElementTrait;

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
