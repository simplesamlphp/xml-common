<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\DateTimeTest;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\DateTimeValue;

/**
 * Class \SimpleSAML\Test\XML\Type\DateTimeValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(DateTimeValue::class)]
final class DateTimeValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $dateTime
     */
    #[DataProvider('provideInvalidDateTime')]
    #[DataProvider('provideValidDateTime')]
    #[DataProviderExternal(DateTimeTest::class, 'provideValidDateTime')]
    #[DependsOnClass(DateTimeTest::class)]
    public function testDateTime(bool $shouldPass, string $dateTime): void
    {
        try {
            DateTimeValue::fromString($dateTime);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * Test the fromDateTime function
     */
    #[DependsOnClass(DateTimeTest::class)]
    public function testFromDateTime(): void
    {
        $dt = new DateTimeImmutable('@946684800');

        $dateTimeValue = DateTimeValue::fromDateTime($dt);
        $this->assertEquals('2000-01-01T00:00:00+00:00', $dateTimeValue->getValue());
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidDateTime(): array
    {
        return [
            'whitespace collapse' => [true, ' 2001-10-26T21:32:52 '],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidDateTime(): array
    {
        return [
            'empty' => [false, ''],
            'missing time' => [false, '2001-10-26'],
            'missing second' => [false, '2001-10-26T21:32'],
            'hour out of range' => [false, '2001-10-26T25:32:52+02:00'],
            'year 0000' => [false, '0000-10-26T25:32:52+02:00'],
            'prefixed zero' => [false, '02001-10-26T25:32:52+02:00'],
            'wrong format' => [false, '01-10-26T21:32'],
        ];
    }
}
