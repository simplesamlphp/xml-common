<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\ShortTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class ShortTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $short
     */
    #[DataProvider('provideInvalidShort')]
    #[DataProvider('provideValidShort')]
    public function testValidShort(bool $shouldPass, string $short): void
    {
        try {
            Assert::validShort($short);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidShort(): array
    {
        return [
            'valid positive signed' => [true, '+32767'],
            'valid negative signed' => [true, '-32768'],
            'valid non-signed' => [true, '123'],
            'valid leading zeros' => [true, '-0001'],
            'valid zero' => [true, '0'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidShort(): array
    {
        return [
            'empty' => [false, ''],
            'invalid positive signed out-of-bounds' => [false, '+32768'],
            'invalid negative signed out-of-bounds' => [false, '-32769'],
            'invalid with space' => [false, '1 234'],
            'invalid with fractional' => [false, '1234.'],
            'invalid with thousands-delimiter' => [false, '+1,234'],
        ];
    }
}
