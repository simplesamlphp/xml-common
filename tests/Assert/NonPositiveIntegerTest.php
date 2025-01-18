<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
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
    #[DataProvider('provideNonPositiveInteger')]
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
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideNonPositiveInteger(): array
    {
        return [
            'empty' => [false, ''],
            'valid non-positive integer' => [true, '-123456'],
            'valid zero' => [true, '0'],
            'valid negative leading zeros' => [true, '-0000000000000000000005'],
            'invalid with fractional' => [false, '-1.'],
            'invalid positive' => [false, '1234'],
            'invalid with thousands-delimiter' => [false, '-1,234'],
        ];
    }
}
