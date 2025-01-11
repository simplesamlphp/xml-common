<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Assert;

use InvalidArgumentException;

/**
 * @package simplesamlphp/xml-common
 */
trait LangTrait
{
    /** @var string */
    private static string $lang_regex = '/^([a-z]{2}|[i]-[a-z]+|[x]-[a-z]{1,8})(-[a-z]{1,8})*$/Di';


    /**
     * @param string $value
     * @param string $message
     */
    protected static function validLang(string $value, string $message = ''): void
    {
        Assert::regex(
            $value,
            self::$lang_regex,
            $message ?: '%s is not a valid xs:language',
            InvalidArgumentException::class,
        );
    }
}