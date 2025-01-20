<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\DateTest;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\DateValue;

/**
 * Class \SimpleSAML\Test\XML\Type\DateValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(DateValue::class)]
final class DateValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $date
     */
    #[DataProvider('provideInvalidDate')]
    #[DataProvider('provideValidDate')]
    #[DataProviderExternal(DateTest::class, 'provideValidDate')]
    #[DependsOnClass(DateTest::class)]
    public function testDate(bool $shouldPass, string $date): void
    {
        try {
            DateValue::fromString($date);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidDate(): array
    {
        return [
            'whitespace collapse' => [true, " 2001-10-26 \n"],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidDate(): array
    {
        return [
            'empty' => [false, ''],
            'missing part' => [false, '2001-10'],
            'day out of range' => [false, '2001-10-32'],
            'month out of range' => [false, '2001-13-26+02:00'],
            'century part missing' => [false, '01-10-26'],
        ];
    }
}
