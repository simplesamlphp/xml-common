<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\NonPositiveIntegerTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class NonPositiveIntegerTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $nonPositiveInteger
     */
    #[DataProvider('provideInvalidNonPositiveInteger')]
    #[DataProvider('provideValidNonPositiveInteger')]
    public function testValidNonPositiveInteger(bool $shouldPass, string $nonPositiveInteger): void
    {
        try {
            Assert::validNonPositiveInteger($nonPositiveInteger);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidNonPositiveInteger(): array
    {
        return [
            'valid non-positive integer' => [true, '-123456'],
            'valid zero' => [true, '0'],
            'valid negative leading zeros' => [true, '-0000000000000000000005'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidNonPositiveInteger(): array
    {
        return [
            'empty' => [false, ''],
            'invalid with fractional' => [false, '-1.'],
            'invalid positive' => [false, '1234'],
            'invalid with thousands-delimiter' => [false, '-1,234'],
        ];
    }
}
