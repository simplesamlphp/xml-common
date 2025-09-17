<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\PositiveIntegerTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class PositiveIntegerTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $positiveInteger
     */
    #[DataProvider('provideInvalidPositiveInteger')]
    #[DataProvider('provideValidPositiveInteger')]
    public function testValidPositiveInteger(bool $shouldPass, string $positiveInteger): void
    {
        try {
            Assert::validPositiveInteger($positiveInteger);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidPositiveInteger(): array
    {
        return [
            'valid positive integer' => [true, '123456'],
            'valid positive ten' => [true, '30'],
            'valid positive hundred' => [true, '300'],
            'valid signed positive integer' => [true, '+123456'],
            'valid single digit' => [true, '3'],
            'valid negative leading zeros' => [true, '0000000000000000000005'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidPositiveInteger(): array
    {
        return [
            'empty' => [false, ''],
            'invalid zero' => [false, '0'],
            'invalid with fractional' => [false, '1.'],
            'invalid negative' => [false, '-1234'],
            'invalid with thousands-delimiter' => [false, '1,234'],
        ];
    }
}
