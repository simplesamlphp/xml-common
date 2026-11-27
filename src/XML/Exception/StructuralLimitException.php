<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Exception;

/**
 * Class StructuralLimitException
 *
 * This exception is thrown when one of the configured structural limits
 *   have been reached during processing in the library.
 *
 * @package simplesamlphp/xml-common
 */
class StructuralLimitException extends RuntimeException
{
}
