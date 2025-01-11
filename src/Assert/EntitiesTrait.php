<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Assert;

use InvalidArgumentException;

/**
 * @package simplesamlphp/xml-common
 */
trait EntitiesTrait
{
    /** @var string */
    private static string $entities_regex = '/^([a-zA-Z_][\w.-]*)([\s][a-zA-Z_][\w.-]*)*$/Du';


    /**
     * @param string $value
     * @param string $message
     */
    protected static function validEntities(string $value, string $message = ''): void
    {
        Assert::regex(
            $value,
            self::$entities_regex,
            $message ?: '%s is not a valid xs:ENTITIES',
            InvalidArgumentException::class,
        );
    }
}
