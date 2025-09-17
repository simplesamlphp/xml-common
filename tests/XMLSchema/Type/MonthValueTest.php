<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\DependsOnClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\MonthTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\MonthValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\MonthValueTest
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
    #[DataProvider('provideInvalidMonth')]
    #[DataProvider('provideValidMonth')]
    #[DataProviderExternal(MonthTest::class, 'provideValidMonth')]
    #[DependsOnClass(MonthTest::class)]
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
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidMonth(): array
    {
        return [
            'whitespace collapse' => [true, ' --05  '],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidMonth(): array
    {
        return [
            'empty' => [false, ''],
            'invalid format' => [false, '-01-'],
            'month out of range' => [false, '--13'],
            'both digits must be provided' => [false, '--1'],
            'missing leading dashes' => [false, '01'],
        ];
    }
}
