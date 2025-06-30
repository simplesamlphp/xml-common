<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\Type\Schema;

use SimpleSAML\XMLSchema\Type\NonNegativeIntegerValue;

/**
 * @package simplesaml/xml-common
 */
class MinOccursValue extends NonNegativeIntegerValue
{
    /** @var string */
    public const SCHEMA_TYPE = 'minOccurs';
}
