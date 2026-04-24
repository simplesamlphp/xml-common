<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\DependsOnClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\DateTimeTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\DateTimeValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\DateTimeValueTest
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
     */
    public function testSubSeconds(): void
    {
        // Strip sub-second trailing zero's and make sure the decimal sign is removed
        $dateTimeValue = DateTimeValue::fromString('2001-10-26T21:32:52.00');
        $this->assertEquals('2001-10-26T21:32:52', $dateTimeValue->getValue());

        // Strip sub-second trailing zero's
        $dateTimeValue = DateTimeValue::fromString('2001-10-26T21:32:52.12300');
        $this->assertEquals('2001-10-26T21:32:52.123', $dateTimeValue->getValue());

        // Strip sub-seconds over microsecond precision
        $dateTimeValue = DateTimeValue::fromString('2001-10-26T21:32:52.1234567');
        $this->assertEquals('2001-10-26T21:32:52.123456', $dateTimeValue->getValue());

        // Strip sub-second trailing zero's and make sure the decimal sign is removed
        $dateTimeValue = DateTimeValue::fromString('2001-10-26T21:32:52.00Z');
        $this->assertEquals('2001-10-26T21:32:52Z', $dateTimeValue->getValue());

        // Strip sub-seconds over microsecond precision with timezone
        $dateTimeValue = DateTimeValue::fromString('2001-10-26T21:32:52.1234567+01:00');
        $this->assertEquals('2001-10-26T21:32:52.123456+01:00', $dateTimeValue->getValue());

        // Strip sub-seconds over microsecond precision with timezone Zulu
        $dateTimeValue = DateTimeValue::fromString('2001-10-26T21:32:52.1234567Z');
        $this->assertEquals('2001-10-26T21:32:52.123456Z', $dateTimeValue->getValue());
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidDateTime(): array
    {
        return [
            'whitespace collapse' => [true, ' 2001-10-26T21:32:52 '],
            'trailing sub-second zero' => [true, '2001-10-26T21:32:52.1230'],
            'trailing sub-second zero with timezone' => [true, '2001-10-26T21:32:52.1230+00:00'],
            'trailing sub-second zero with timezone Zulu' => [true, '2001-10-26T21:32:52.1230Z'],
            'all trailing sub-second zero' => [true, '2001-10-26T21:32:52.00'],
            'all trailing sub-second zero with timezone' => [true, '2001-10-26T21:32:52.00+00:00'],
            'all trailing sub-second zero with timezone Zulu' => [true, '2001-10-26T21:32:52.00Z'],
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
