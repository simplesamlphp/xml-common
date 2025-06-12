<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Assert;

/**
 * @package simplesamlphp/xml-common
 */
trait StringTrait
{
    /**
     * @param string $value
     * @param string $message
     */
    protected static function validString(string $value, string $message = ''): void
    {
    }
}
