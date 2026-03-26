<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Assert;

use InvalidArgumentException;

/**
 * @package simplesamlphp/xml-common
 */
trait TimeTrait
{
    /**
     * The ·lexical space· of time consists of finite-length sequences of characters of the form:
     * hh ':' mm ':' ss ('.' s+)? (zzzzzz)?, where
     *
     * * hh is a two-digit numeral that represents the hour; '24' is permitted if the minutes and seconds represented
     *   are zero, and the dateTime value so represented is the first instant of the following day
     *   (the hour property of a dateTime object in the ·value space· cannot have a value greater than 23);
     * * ':' is a separator between parts of the time-of-day portion;
     * * the second mm is a two-digit numeral that represents the minute;
     * * ss is a two-integer-digit numeral that represents the whole seconds;
     * * '.' s+ (if present) represents the fractional seconds;
     * * zzzzzz (if present) represents the timezone (as described below).
     *
     * Except for trailing fractional zero digits in the seconds representation, '24:00:00' time representations,
     * and timezone (for timezoned values), the mapping from literals to values is one-to-one.
     * Where there is more than one possible representation, the canonical representation is as follows:
     *
     * The 2-digit numeral representing the hour must not be '24';
     * The fractional second string, if present, must not end in '0';
     * for timezoned values, the timezone must be represented with 'Z' (All timezoned dateTime values are UTC.).
     *
     * Note: we're restricting decimal seconds to 12, although strictly the standards allow an infite number.
     *
     * We know for a fact that Apereo CAS v7.0.x uses 9 decimals
     */
    private static string $time_regex = '/^
        ([0-1][0-9]|2[0-3])
        :
        (0[0-9]|[1-5][0-9])
        :(0[0-9]|[1-5][0-9])
        (\.[0-9]{0,11}[1-9])?
        ([+-]([0-1][0-9]|2[0-4]):(0[0-9]|[1-5][0-9])|Z)?
        $/Dx';


    /**
     * @param string $value
     * @param string $message
     */
    protected static function validTime(string $value, string $message = ''): void
    {
        parent::regex(
            $value,
            self::$time_regex,
            $message ?: '%s is not a valid xs:time',
            InvalidArgumentException::class,
        );
    }
}
