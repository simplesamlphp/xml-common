<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\DayTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Builtin\DayValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\Builtin\DayValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(DayValue::class)]
final class DayValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $day
     */
    #[DataProvider('provideInvalidDay')]
    #[DataProvider('provideValidDay')]
    #[DataProviderExternal(DayTest::class, 'provideValidDay')]
    #[DependsOnClass(DayTest::class)]
    public function testDay(bool $shouldPass, string $day): void
    {
        try {
            DayValue::fromString($day);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidDay(): array
    {
        return [
            'whitespace collapse' => [true, ' ---03 '],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidDay(): array
    {
        return [
            'empty' => [false, ''],
            'invalid format' => [false, '--30-'],
            'day out of range' => [false, '---35'],
            'missing leading dashes' => [false, '15'],
        ];
    }
}
