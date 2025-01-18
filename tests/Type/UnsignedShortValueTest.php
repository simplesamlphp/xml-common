<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\UnsignedShortValue;

/**
 * Class \SimpleSAML\Test\XML\Type\UnsignedShortValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(UnsignedShortValue::class)]
final class UnsignedShortValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $unsignedShort
     */
    #[DataProvider('provideUnsignedShort')]
    public function testUnsignedShort(bool $shouldPass, string $unsignedShort): void
    {
        try {
            UnsignedShortValue::fromString($unsignedShort);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideUnsignedShort(): array
    {
        return [
            'empty' => [false, ''],
            'valid positive short' => [true, '65535'],
            'invalid positive out-of-bounds' => [false, '65536'],
            'valid signed positive short' => [true, '+65535'],
            'valid zero' => [true, '0'],
            'valid negative leading zeros' => [true, '0000000000000000000005'],
            'invalid with fractional' => [false, '1.'],
            'valid with whitespace collapse' => [true, " 1 234 \n"],
            'invalid negative' => [false, '-1'],
            'invalid with thousands-delimiter' => [false, '1,234'],
        ];
    }
}
