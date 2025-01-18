<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\UnsignedLongValue;

/**
 * Class \SimpleSAML\Test\XML\Type\UnsignedLongValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(UnsignedLongValue::class)]
final class UnsignedLongValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $unsignedLong
     */
    #[DataProvider('provideUnsignedLong')]
    public function testUnsignedLong(bool $shouldPass, string $unsignedLong): void
    {
        try {
            UnsignedLongValue::fromString($unsignedLong);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideUnsignedLong(): array
    {
        return [
            'empty' => [false, ''],
            'valid positive integer' => [true, '18446744073709551615'],
            'invalid positive out-of-bounds' => [false, '18446744073709551616'],
            'valid signed positive integer' => [true, '+18446744073709551615'],
            'valid zero' => [true, '0'],
            'valid negative leading zeros' => [true, '0000000000000000000005'],
            'invalid with fractional' => [false, '1.'],
            'valid with whitespace collapse' => [true, " 1 234 \n"],
            'invalid negative' => [false, '-1'],
            'invalid with thousands-delimiter' => [false, '1,234'],
        ];
    }
}
