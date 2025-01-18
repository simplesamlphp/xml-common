<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\DayValue;

/**
 * Class \SimpleSAML\Test\XML\Type\DayValueTest
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
    #[DataProvider('provideDay')]
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
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideDay(): array
    {
        return [
            'empty' => [false, ''],
            'whitespace collapse' => [true, ' ---03 '],
            'valid' => [true, '---01'],
            'valid numeric timezone' => [true, '---01+02:00'],
            'valid Zulu timezone' => [true, '---01Z'],
            'valid 00:00 timezone' => [true, '---01+00:00'],
            'day 15' => [true, '---15'],
            'day 31' => [true, '---31'],
            'invalid format' => [false, '--30-'],
            'day out of range' => [false, '---35'],
            'missing leading dashes' => [false, '15'],
        ];
    }
}
