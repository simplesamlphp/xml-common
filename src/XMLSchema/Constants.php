<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema;

use SimpleSAML\XML\Constants as BaseConstants;

/**
 * Various XML constants.
 *
 * @package simplesamlphp/xml-common
 */
class Constants extends BaseConstants
{
    /**
     * The namespace for XML schema.
     */
    public const NS_XS = 'http://www.w3.org/2001/XMLSchema';

    /**
     * The namespace for XML schema instance.
     */
    public const NS_XSI = 'http://www.w3.org/2001/XMLSchema-instance';
}
