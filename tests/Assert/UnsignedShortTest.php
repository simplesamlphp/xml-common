<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\UnsignedShortTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class UnsignedShortTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $unsignedShort
     */
    #[DataProvider('provideUnsignedShort')]
    public function testValidUnsignedShort(bool $shouldPass, string $unsignedShort): void
    {
        try {
            Assert::validUnsignedShort($unsignedShort);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
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
            'invalid with space' => [false, '12 34'],
            'invalid negative' => [false, '-1'],
            'invalid with thousands-delimiter' => [false, '1,234'],
        ];
    }
}
