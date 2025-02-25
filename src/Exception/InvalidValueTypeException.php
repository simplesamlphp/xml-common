<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Exception;

use InvalidArgumentException;

/**
 * This exception may be raised when a value type does not implement \SimpleSAML\XML\Type\ValueTypeInterface.
 *
 * @package simplesamlphp/xml-common
 */
class InvalidValueTypeException extends InvalidArgumentException
{
}
