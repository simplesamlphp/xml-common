<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\UnsignedByteTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class UnsignedByteTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $unsignedByte
     */
    #[DataProvider('provideInvalidUnsignedByte')]
    #[DataProvider('provideValidUnsignedByte')]
    public function testValidUnsignedByte(bool $shouldPass, string $unsignedByte): void
    {
        try {
            Assert::validUnsignedByte($unsignedByte);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidUnsignedByte(): array
    {
        return [
            'valid positive Byte' => [true, '255'],
            'valid signed positive Byte' => [true, '+255'],
            'valid zero' => [true, '0'],
            'valid negative leading zeros' => [true, '0000000000000000000005'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidUnsignedByte(): array
    {
        return [
            'empty' => [false, ''],
            'invalid positive out-of-bounds' => [false, '256'],
            'invalid with fractional' => [false, '1.'],
            'invalid with space' => [false, '12 3'],
            'invalid negative' => [false, '-1'],
            'invalid with thousands-delimiter' => [false, '1,23'],
        ];
    }
}
