<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\UnsignedIntTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class UnsignedIntTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $unsignedInt
     */
    #[DataProvider('provideUnsignedInt')]
    public function testValidUnsignedInt(bool $shouldPass, string $unsignedInt): void
    {
        try {
            Assert::validUnsignedInt($unsignedInt);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideUnsignedInt(): array
    {
        return [
            'empty' => [false, ''],
            'valid positive integer' => [true, '4294967295'],
            'invalid positive out-of-bounds' => [false, '4294967296'],
            'valid signed positive integer' => [true, '+4294967295'],
            'valid zero' => [true, '0'],
            'valid negative leading zeros' => [true, '0000000000000000000005'],
            'invalid with fractional' => [false, '1.'],
            'invalid with space' => [false, '12 34'],
            'invalid negative' => [false, '-1'],
            'invalid with thousands-delimiter' => [false, '1,234'],
        ];
    }
}
