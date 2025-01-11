<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\DurationValue;

/**
 * Class \SimpleSAML\Test\Type\DurationValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(DurationValue::class)]
final class DurationValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $duration
     */
    #[DataProvider('provideDuration')]
    public function testDuration(bool $shouldPass, string $duration): void
    {
        try {
            DurationValue::fromString($duration);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideDuration(): array
    {
        return [
            'empty' => [false, ''],
            'whitespace collapse' => [true, '  PT130S '],
            'valid long seconds' => [true, 'PT1004199059S'],
            'valid short seconds' => [true, 'PT130S'],
            'valid minutes and seconds' => [true, 'PT2M10S'],
            'valid one day and two seconds' => [true, 'P1DT2S'],
            'valid minus one year' => [true, '-P1Y'],
            'valid complex sub-second' => [true, 'P1Y2M3DT5H20M30.123S'],
            'invalid missing P' => [false, '1Y'],
            'invalid missing T' => [false, 'P1S'],
            'invalid all parts must be positive' => [false, 'P-1Y'],
            'invalid order Y must precede M' => [false, 'P1M2Y'],
            'invalid all parts must me positive' => [false, 'P1Y-1M'],
        ];
    }
}
