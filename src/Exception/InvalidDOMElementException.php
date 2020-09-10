<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Exception;

use InvalidArgumentException;

/**
 * This exception may be raised when the passed DOMElement is of the wrong type
 *
 * @package simplesamlphp/saml2
 */
class InvalidDOMElementException extends InvalidArgumentException
{
}
