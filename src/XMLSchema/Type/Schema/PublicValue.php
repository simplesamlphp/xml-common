<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Type\Schema;

use SimpleSAML\XMLSchema\Type\TokenValue;

/**
 * @package simplesaml/xml-common
 */
class PublicValue extends TokenValue
{
    /** @var string */
    public const SCHEMA_TYPE = 'public';
}
