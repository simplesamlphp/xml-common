<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\FloatTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class FloatTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $float
     */
    #[DataProvider('provideInvalidFloat')]
    #[DataProvider('provideValidFloat')]
    public function testValidFloat(bool $shouldPass, string $float): void
    {
        try {
            Assert::validFloat($float);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidFloat(): array
    {
        return [
            'valid positive signed' => [true, '+123.456'],
            'valid negative signed' => [true, '-123.456'],
            'valid non-signed' => [true, '123.456'],
            'valid leading zeros' => [true, '-0123.456'],
            'valid zero' => [true, '0.0'],
            'valid NaN' => [true, 'NaN'],
            'valid negative FIN' => [true, '-FIN'],
            'valid FIN' => [true, 'FIN'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidFloat(): array
    {
        return [
            'empty' => [false, ''],
            'case-sensitive NaN' => [false, 'NAN'],
            'invalid +FIN' => [false, '+FIN'],
            'invalid with space' => [false, '1 23.0'],
            'invalid without fractional' => [false, '123'],
        ];
    }
}
