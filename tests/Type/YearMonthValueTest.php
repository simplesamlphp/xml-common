<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\YearMonthValue;

/**
 * Class \SimpleSAML\Test\Type\YearMonthValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(YearMonthValue::class)]
final class YearMonthValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $yearMonth
     */
    #[DataProvider('provideYearMonth')]
    public function testYearMonth(bool $shouldPass, string $yearMonth): void
    {
        try {
            YearMonthValue::fromString($yearMonth);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideYearMonth(): array
    {
        return [
            'empty' => [false, ''],
            'whitespace collapse' => [true, ' 2001-10  '],
            'valid' => [true, '2001-10'],
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
