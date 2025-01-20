<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\ByteTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class ByteTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $byte
     */
    #[DataProvider('provideInvalidByte')]
    #[DataProvider('provideValidByte')]
    public function testValidByte(bool $shouldPass, string $byte): void
    {
        try {
            Assert::validByte($byte);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidByte(): array
    {
        return [
            'valid positive signed' => [true, '+127'],
            'valid negative signed' => [true, '-128'],
            'valid non-signed' => [true, '123'],
            'valid leading zeros' => [true, '-0001'],
            'valid zero' => [true, '0'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidByte(): array
    {
        return [
            'empty' => [false, ''],
            'invalid positive signed out-of-bounds' => [false, '+128'],
            'invalid negative signed out-of-bounds' => [false, '-129'],
            'invalid with space' => [false, '1 23'],
            'invalid with fractional' => [false, '123.'],
        ];
    }
}
