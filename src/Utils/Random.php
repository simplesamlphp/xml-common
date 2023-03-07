<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Utils;

use Ramsey\Uuid\Uuid;
use SimpleSAML\Assert\Assert;

/**
 * @package simplesamlphp/xml-common
 */
class Random
{
    /**
     * This function will generate a unique ID that is valid for use
     * in an xs:ID attribute
     */
    public function generateID(): string
    {
        return '_' . Uuid::uuid4()->toString();
    }
}
