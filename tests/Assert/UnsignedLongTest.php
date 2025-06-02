<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\UnsignedLongTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class UnsignedLongTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $unsignedLong
     */
    #[DataProvider('provideInvalidUnsignedLong')]
    #[DataProvider('provideValidUnsignedLong')]
    public function testValidUnsignedLong(bool $shouldPass, string $unsignedLong): void
    {
        try {
            Assert::validUnsignedLong($unsignedLong);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidUnsignedLong(): array
    {
        return [
            'valid positive integer' => [true, '18446744073709551615'],
            'valid signed positive integer' => [true, '+18446744073709551615'],
            'valid zero' => [true, '0'],
            'valid negative leading zeros' => [true, '0000000000000000000005'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidUnsignedLong(): array
    {
        return [
            'empty' => [false, ''],
            'invalid positive out-of-bounds' => [false, '18446744073709551616'],
            'invalid with fractional' => [false, '1.'],
            'invalid with space' => [false, '12 34'],
            'invalid negative' => [false, '-1'],
            'invalid with thousands-delimiter' => [false, '1,234'],
        ];
    }
}
