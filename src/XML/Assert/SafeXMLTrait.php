<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Assert;

use Dom;
use InvalidArgumentException;
use SimpleSAML\XML\Exception\StructuralLimitException;
use SimpleSAML\XML\Limit\XMLLimit;

/**
 * @package simplesamlphp/xml-common
 */
trait SafeXMLTrait
{
    /**
     * @param \Dom\Element $value
     * @param string $message
     */
    protected static function assertSafeXML(Dom\Element $value, string $message = ''): void
    {
        $config = XMLLimit::getDefault();

        try {
            XMLLimit::assertSafeXML($value, $config);
        } catch (StructuralLimitException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
    }
}
