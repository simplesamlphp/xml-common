<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\SerializableElementTrait;
use SimpleSAML\XML\StringElementTrait;

/**
 * Empty shell class for testing String elements.
 *
 * @package simplesaml/xml-common
 */
final class StringElement extends AbstractElement
{
    use StringElementTrait;

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
