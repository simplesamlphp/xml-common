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
     * The namespace fox XML.
     */
    public const NS_XML = 'http://www.w3.org/XML/1998/namespace';

    /**
     * The namespace fox XML schema.
     */
    public const NS_XS = 'http://www.w3.org/2001/XMLSchema';

    /**
     * The namespace for XML schema instance.
     */
    public const NS_XSI = 'http://www.w3.org/2001/XMLSchema-instance';

    /**
     * The maximum amount of child nodes this library is willing to handle.
     */
    public const UNBOUNDED_LIMIT = 10000;
}
