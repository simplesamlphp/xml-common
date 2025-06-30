<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

/**
 * Various XML constants.
 *
 * @package simplesamlphp/xml-common
 */
class Constants
{
    /**
     * The namespace for XML.
     */
    public const NS_XML = 'http://www.w3.org/XML/1998/namespace';

    /**
     * The maximum amount of child nodes this library is willing to handle.
     * By specification, this limit is 150K, but that opens up for denial of service.
     */
    public const UNBOUNDED_LIMIT = 10000;
}
