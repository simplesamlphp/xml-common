<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\DependsOnClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\YearTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\YearValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\YearValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(YearValue::class)]
final class YearValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $year
     */
    #[DataProvider('provideInvalidYear')]
    #[DataProvider('provideValidYear')]
    #[DataProviderExternal(YearTest::class, 'provideValidYear')]
    #[DependsOnClass(YearTest::class)]
    public function testYear(bool $shouldPass, string $year): void
    {
        try {
            YearValue::fromString($year);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidYear(): array
    {
        return [
            'whitespace collapse' => [true, " 2001 \n "],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidYear(): array
    {
        return [
            'empty' => [false, ''],
            'no a year' => [false, 'foobar'],
        ];
    }
}
