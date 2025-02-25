<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\IntegerTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class IntegerTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $integer
     */
    #[DataProvider('provideInvalidInteger')]
    #[DataProvider('provideValidInteger')]
    public function testValidInteger(bool $shouldPass, string $integer): void
    {
        try {
            Assert::validInteger($integer);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidInteger(): array
    {
        return [
            'valid integer' => [true, '123456'],
            'valid positive signed' => [true, '+00000012'],
            'valid negative signed' => [true, '-1'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidInteger(): array
    {
        return [
            'empty' => [false, ''],
            'invalid with space' => [false, '1 234'],
            'invalid with fractional' => [false, '1234.'],
            'invalid with thousands-delimiter' => [false, '+1,234'],
        ];
    }
}
