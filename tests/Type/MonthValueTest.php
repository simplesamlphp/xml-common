<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\MonthValue;

/**
 * Class \SimpleSAML\Test\XML\Type\MonthValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(MonthValue::class)]
final class MonthValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $month
     */
    #[DataProvider('provideMonth')]
    public function testMonth(bool $shouldPass, string $month): void
    {
        try {
            MonthValue::fromString($month);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideMonth(): array
    {
        return [
            'empty' => [false, ''],
            'whitespace collapse' => [true, ' --05  '],
            'valid' => [true, '--05'],
            'valid numeric timezone' => [true, '--11+02:00'],
            'valid Zulu timezone' => [true, '--11Z'],
            'valid 00:00 timezone' => [true, '--11+00:00'],
            'month 02' => [true, '--02'],
            'invalid format' => [false, '-01-'],
            'month out of range' => [false, '--13'],
            'both digits must be provided' => [false, '--1'],
            'missing leading dashes' => [false, '01'],
        ];
    }
}
