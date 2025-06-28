<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\YearMonthTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Builtin\YearMonthValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\Builtin\YearMonthValueTest
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
    #[DataProvider('provideInvalidYearMonth')]
    #[DataProvider('provideValidYearMonth')]
    #[DataProviderExternal(YearMonthTest::class, 'provideValidYearMonth')]
    #[DependsOnClass(YearMonthTest::class)]
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
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidYearMonth(): array
    {
        return [
            'whitespace collapse' => [true, " 2001-10 \n "],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidYearMonth(): array
    {
        return [
            'empty' => [false, ''],
            'missing part' => [false, '2001'],
            'month out of range' => [false, '2001-13'],
        ];
    }
}
