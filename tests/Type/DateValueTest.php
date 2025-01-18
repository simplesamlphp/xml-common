<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
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
    #[DataProvider('provideDate')]
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
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideDate(): array
    {
        return [
            'valid' => [true, '2001-10-26'],
            'valid numeric timezone' => [true, '2001-10-26+02:00'],
            'valid Zulu timezone' => [true, '2001-10-26Z'],
            'valid 00:00 timezone' => [true, '2001-10-26+00:00'],
            '2001 BC' => [true, '-2001-10-26'],
            '2000 BC' => [true, '-20000-04-01'],
            'empty' => [false, ''],
            'whitespace collapse' => [true, ' 2001-10-26 '],
            'missing part' => [false, '2001-10'],
            'day out of range' => [false, '2001-10-32'],
            'month out of range' => [false, '2001-13-26+02:00'],
            'century part missing' => [false, '01-10-26'],
        ];
    }
}
