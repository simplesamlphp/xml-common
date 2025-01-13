<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\DoubleTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class DoubleTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $double
     */
    #[DataProvider('provideDouble')]
    public function testValidDouble(bool $shouldPass, string $double): void
    {
        try {
            Assert::validDouble($double);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideDouble(): array
    {
        return [
            'empty' => [false, ''],
            'valid positive signed' => [true, '+123.456'],
            'valid negative signed' => [true, '-123.456'],
            'valid non-signed' => [true, '123.456'],
            'valid leading zeros' => [true, '-0123.456'],
            'valid zero' => [true, '0.0'],
            'valid NaN' => [true, 'NaN'],
            'case-sensitive NaN' => [false, 'NAN'],
            'valid negative FIN' => [true, '-FIN'],
            'valid FIN' => [true, 'FIN'],
            'invalid +FIN' => [false, '+FIN'],
            'invalid with space' => [false, '1 23.0'],
            'invalid without fractional' => [false, '123'],
        ];
    }
}