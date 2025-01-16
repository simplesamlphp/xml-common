<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Assert;

use InvalidArgumentException;

/**
 * @package simplesamlphp/xml-common
 */
trait QNameTrait
{
    /** @var string */
    private static string $qname_regex = '/^([a-z_][\w.-]*)(:[a-z_][\w.-]*)?$/Dui';

    /***********************************************************************************
     *  NOTE:  Custom assertions may be added below this line.                         *
     *         They SHOULD be marked as `protected` to ensure the call is forced       *
     *          through __callStatic().                                                *
     *         Assertions marked `public` are called directly and will                 *
     *          not handle any custom exception passed to it.                          *
     ***********************************************************************************/


    /**
     * @param string $value
     * @param string $message
     */
    protected static function validQName(string $value, string $message = ''): void
    {
        parent::regex(
            $value,
            self::$qname_regex,
            $message ?: '%s is not a valid xs:QName',
            InvalidArgumentException::class,
        );
    }
}
