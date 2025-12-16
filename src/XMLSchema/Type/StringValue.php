<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Type;

use SimpleSAML\XMLSchema\Type\Interface\AbstractAnySimpleType;

/**
 * @package simplesaml/xml-common
 */
class StringValue extends AbstractAnySimpleType
{
    public const string SCHEMA_TYPE = 'string';
}
