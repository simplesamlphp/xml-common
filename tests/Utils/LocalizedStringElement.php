<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use SimpleSAML\XML\AbstractXMLElement;
use SimpleSAML\XML\LocalizedStringElementTrait;

/**
 * Empty shell class for testing LocalizedStringElement.
 *
 * @package simplesaml/xml-common
 */
final class LocalizedStringElement extends AbstractXMLElement
{
    use LocalizedStringElementTrait;

    /** @var string */
    public const NS = 'urn:x-simplesamlphp:namespace';

    /** @var string */
    public const NS_PREFIX = 'ssp';


    /**
     * @param string $language
     * @param string $content
     */
    public function __construct(string $language, string $content)
    {
        $this->setLanguage($language);
        $this->setContent($content);
    }
}
