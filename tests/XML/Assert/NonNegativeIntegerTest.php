<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\NonNegativeIntegerTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class NonNegativeIntegerTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $nonNegativeInteger
     */
    #[DataProvider('provideInvalidNonNegativeInteger')]
    #[DataProvider('provideValidNonNegativeInteger')]
    public function testValidNonNegativeInteger(bool $shouldPass, string $nonNegativeInteger): void
    {
        try {
            Assert::validNonNegativeInteger($nonNegativeInteger);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidNonNegativeInteger(): array
    {
        return [
            'valid positive integer' => [true, '123456'],
            'valid signed positive integer' => [true, '+123456'],
            'valid zero' => [true, '0'],
            'valid negative leading zeros' => [true, '0000000000000000000005'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidNonNegativeInteger(): array
    {
        return [
            'empty' => [false, ''],
            'invalid with fractional' => [false, '1.'],
            'invalid negative' => [false, '-1234'],
            'invalid with thousands-delimiter' => [false, '1,234'],
        ];
    }
}
