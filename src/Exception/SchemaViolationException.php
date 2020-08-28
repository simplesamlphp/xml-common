<?php

declare(strict_types=1);

namespace SAML2\Exception;

use InvalidArgumentException;

/**
 * This exception may be raised when a violation of the SAML2 schema is detected
 *
 * @author Tim van Dijen, <tvdijen@gmail.com>
 * @package simplesamlphp/saml2
 */
class SchemaViolationException extends InvalidArgumentException
{
}
