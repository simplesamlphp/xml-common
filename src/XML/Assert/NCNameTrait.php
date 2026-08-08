<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Assert;

use InvalidArgumentException;

/**
 * @package simplesamlphp/xml-common
 */
trait NCNameTrait
{
    private static string $ncname_regex = '/
(?(DEFINE)
  (?<NCNameStartChar>
    [A-Z_a-z\x{C0}-\x{D6}\x{D8}-\x{F6}\x{F8}-\x{2FF}\x{370}-\x{37D}\x{37F}-\x{1FFF}\x{200C}-\x{200D}\x{2070}-\x{218F}\x{2C00}-\x{2FEF}\x{3001}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFFD}\x{10000}-\x{EFFFF}]
  )
  (?<NCNameChar>
    (?&NCNameStartChar)
    |[-.0-9\x{B7}\x{0300}-\x{036F}\x{203F}-\x{2040}]
  )
  (?<NCName>
    (?&NCNameStartChar)(?&NCNameChar)*
  )
)
^(?&NCName)$
/uxD';


    /**
     * @param string $value
     * @param string $message
     */
    protected static function validNCName(string $value, string $message = ''): void
    {
        parent::regex(
            $value,
            self::$ncname_regex,
            $message ?: '"%s" is not a valid xs:NCName',
            InvalidArgumentException::class,
        );
    }
}
