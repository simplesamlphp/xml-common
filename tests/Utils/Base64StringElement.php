<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\Base64StringElementTrait;

/**
 * Empty shell class for testing Base64StringElement.
 *
 * @package simplesaml/xml-common
 */
final class Base64StringElement extends AbstractElement
{
    use Base64StringElementTrait;

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
