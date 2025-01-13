<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\ByteValue;

/**
 * Class \SimpleSAML\Test\Type\ByteValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(ByteValue::class)]
final class ByteValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $byte
     */
    #[DataProvider('provideByte')]
    public function testByte(bool $shouldPass, string $byte): void
    {
        try {
            ByteValue::fromString($byte);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideByte(): array
    {
        return [
            'empty' => [false, ''],
            'valid positive signed' => [true, '+127'],
            'valid negative signed' => [true, '-128'],
            'valid non-signed' => [true, '123'],
            'valid leading zeros' => [true, '-0001'],
            'valid zero' => [true, '0'],
            'invalid positive signed out-of-bounds' => [false, '+128'],
            'invalid negative signed out-of-bounds' => [false, '-129'],
            'valid with whitespace collapse' => [true, " 1 23 \n"],
            'invalid with fractional' => [false, '123.'],
        ];
    }
}
