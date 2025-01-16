<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\UnsignedByteValue;

/**
 * Class \SimpleSAML\Test\Type\UnsignedByteValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(UnsignedByteValue::class)]
final class UnsignedByteValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $unsignedByte
     */
    #[DataProvider('provideUnsignedByte')]
    public function testUnsignedByte(bool $shouldPass, string $unsignedByte): void
    {
        try {
            UnsignedByteValue::fromString($unsignedByte);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideUnsignedByte(): array
    {
        return [
            'empty' => [false, ''],
            'valid positive Byte' => [true, '255'],
            'invalid positive out-of-bounds' => [false, '256'],
            'valid signed positive Byte' => [true, '+255'],
            'valid zero' => [true, '0'],
            'valid negative leading zeros' => [true, '0000000000000000000005'],
            'invalid with fractional' => [false, '1.'],
            'valid with whitespace collapse' => [true, " 1 24 \n"],
            'invalid negative' => [false, '-1'],
            'invalid with thousands-delimiter' => [false, '1,23'],
        ];
    }
}
