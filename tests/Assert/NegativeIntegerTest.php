<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\NegativeIntegerTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class NegativeIntegerTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $negativeInteger
     */
    #[DataProvider('provideNegativeInteger')]
    public function testValidNegativeInteger(bool $shouldPass, string $negativeInteger): void
    {
        try {
            Assert::validNegativeInteger($negativeInteger);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideNegativeInteger(): array
    {
        return [
            'empty' => [false, ''],
            'valid non-positive integer' => [true, '-123456'],
            'invalid zero' => [false, '0'],
            'valid negative leading zeros' => [true, '-0000000000000000000005'],
            'invalid with fractional' => [false, '-1.'],
            'invalid positive' => [false, '1234'],
            'invalid with thousands-delimiter' => [false, '-1,234'],
        ];
    }
}
