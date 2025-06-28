<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\TimeTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Builtin\TimeValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\Builtin\TimeValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(TimeValue::class)]
final class TimeValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $time
     */
    #[DataProvider('provideInvalidTime')]
    #[DataProvider('provideValidTime')]
    #[DataProviderExternal(TimeTest::class, 'provideValidTime')]
    #[DependsOnClass(TimeTest::class)]
    public function testTime(bool $shouldPass, string $time): void
    {
        try {
            TimeValue::fromString($time);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidTime(): array
    {
        return [
            'whitespace collapse' =>  [true, "\n  21:32:52.12679\t "],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidTime(): array
    {
        return [
            'invalid negative hour' => [false, '-10:00:00'],
            'invalid hour out of range' => [false, '25:25:10'],
            'invalid invalid format' => [false, '1:20:10'],
        ];
    }
}
