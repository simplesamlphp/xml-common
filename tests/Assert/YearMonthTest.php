<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\YearMonthTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class YearMonthTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $yearMonth
     */
    #[DataProvider('provideYearMonth')]
    public function testValidYearMonth(bool $shouldPass, string $yearMonth): void
    {
        try {
            Assert::validYearMonth($yearMonth);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideYearMonth(): array
    {
        return [
            'valid' => [true, '2001-10'],
            'space' => [false, '200 01-10'],
            'valid numeric timezone' => [true, '2001-10+02:00'],
            'valid Zulu timezone' => [true, '2001-10Z'],
            'valid 00:00 timezone' => [true, '2001-10+00:00'],
            '2001 BC' => [true, '-2001-10'],
            '20000 BC' => [true, '-20000-04'],
            'missing part' => [false, '2001'],
            'month out of range' => [false, '2001-13'],
        ];
    }
}
