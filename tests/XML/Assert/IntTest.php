<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\IntTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class IntTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $int
     */
    #[DataProvider('provideInvalidInt')]
    #[DataProvider('provideValidInt')]
    public function testValidInt(bool $shouldPass, string $int): void
    {
        try {
            Assert::validInt($int);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidInt(): array
    {
        return [
            'valid positive signed' => [true, '+2147483647'],
            'valid negative signed' => [true, '-2147483648'],
            'valid non-signed' => [true, '123'],
            'valid leading zeros' => [true, '-0001'],
            'valid zero' => [true, '0'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidInt(): array
    {
        return [
            'empty' => [false, ''],
            'invalid positive signed out-of-bounds' => [false, '+2147483648'],
            'invalid negative signed out-of-bounds' => [false, '-2147483649'],
            'invalid with space' => [false, '1 234'],
            'invalid with fractional' => [false, '1234.'],
            'invalid with thousands-delimiter' => [false, '+1,234'],
        ];
    }
}
