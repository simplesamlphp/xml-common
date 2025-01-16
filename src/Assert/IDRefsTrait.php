<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Assert;

use InvalidArgumentException;

/**
 * @package simplesamlphp/xml-common
 */
trait IDRefsTrait
{
    /** @var string */
    private static string $idrefs_regex = '/^([a-zA-Z_][\w.-]*)([\s][a-zA-Z_][\w.-]*)*$/Du';


    /**
     * @param string $value
     * @param string $message
     */
    protected static function validIDRefs(string $value, string $message = ''): void
    {
        parent::regex(
            $value,
            self::$idrefs_regex,
            $message ?: '%s is not a valid xs:IDREFS',
            InvalidArgumentException::class,
        );
    }
}
