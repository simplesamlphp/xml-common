<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\DurationTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class DurationTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $duration
     */
    #[DataProvider('provideInvalidDuration')]
    #[DataProvider('provideValidDuration')]
    public function testValidDuration(bool $shouldPass, string $duration): void
    {
        try {
            Assert::validDuration($duration);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidDuration(): array
    {
        return [
            'valid long seconds' => [true, 'PT1004199059S'],
            'valid short seconds' => [true, 'PT130S'],
            'valid minutes and seconds' => [true, 'PT2M10S'],
            'valid one day and two seconds' => [true, 'P1DT2S'],
            'valid minus one year' => [true, '-P1Y'],
            'valid complex sub-second' => [true, 'P1Y2M3DT5H20M30.123S'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidDuration(): array
    {
        return [
            'invalid missing P' => [false, '1Y'],
            'invalid missing T' => [false, 'P1S'],
            'invalid all parts must be positive' => [false, 'P-1Y'],
            'invalid order Y must precede M' => [false, 'P1M2Y'],
            'invalid all parts must me positive' => [false, 'P1Y-1M'],
        ];
    }
}
