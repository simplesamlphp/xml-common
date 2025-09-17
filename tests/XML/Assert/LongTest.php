<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\LongTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class LongTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $long
     */
    #[DataProvider('provideInvalidLong')]
    #[DataProvider('provideValidLong')]
    public function testValidLong(bool $shouldPass, string $long): void
    {
        try {
            Assert::validLong($long);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidLong(): array
    {
        return [
            'valid positive signed' => [true, '+9223372036854775807'],
            'valid negative signed' => [true, '-9223372036854775808'],
            'valid non-signed' => [true, '9223372036854775807'],
            'valid leading zeros' => [true, '-0001'],
            'valid zero' => [true, '0'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidLong(): array
    {
        return [
            'empty' => [false, ''],
            'invalid positive signed out-of-bounds' => [false, '+9223372036854775808'],
            'invalid negative signed out-of-bounds' => [false, '-9223372036854775809'],
            'invalid with space' => [false, '1 234'],
            'invalid with fractional' => [false, '1234.'],
            'invalid with thousands-delimiter' => [false, '+1,234'],
        ];
    }
}
