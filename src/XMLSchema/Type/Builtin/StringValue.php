<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Type\Builtin;

use SimpleSAML\XMLSchema\Type\Helper\AbstractAnySimpleType;

/**
 * @package simplesaml/xml-common
 */
class StringValue extends AbstractAnySimpleType
{
    /** @var string */
    public const SCHEMA_TYPE = 'string';
}
