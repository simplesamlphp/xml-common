<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\MonthTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class MonthTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $month
     */
    #[DataProvider('provideInvalidMonth')]
    #[DataProvider('provideValidMonth')]
    public function testValidMonth(bool $shouldPass, string $month): void
    {
        try {
            Assert::validMonth($month);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidMonth(): array
    {
        return [
            'valid' => [true, '--05'],
            'valid numeric timezone' => [true, '--11+02:00'],
            'valid Zulu timezone' => [true, '--11Z'],
            'valid 00:00 timezone' => [true, '--11+00:00'],
            'month 02' => [true, '--02'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidMonth(): array
    {
        return [
            'invalid format' => [false, '-01-'],
            'month out of range' => [false, '--13'],
            'both digits must be provided' => [false, '--1'],
            'missing leading dashes' => [false, '01'],
        ];
    }
}
