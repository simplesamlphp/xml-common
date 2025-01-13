<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
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
    #[DataProvider('provideShort')]
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
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideShort(): array
    {
        return [
            'empty' => [false, ''],
            'valid positive signed' => [true, '+32767'],
            'valid negative signed' => [true, '-32768'],
            'valid non-signed' => [true, '123'],
            'valid leading zeros' => [true, '-0001'],
            'valid zero' => [true, '0'],
            'invalid positive signed out-of-bounds' => [false, '+32768'],
            'invalid negative signed out-of-bounds' => [false, '-32769'],
            'invalid with space' => [false, '1 234'],
            'invalid with fractional' => [false, '1234.'],
            'invalid with thousands-delimiter' => [false, '+1,234'],
        ];
    }
}
