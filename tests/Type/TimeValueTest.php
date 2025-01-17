<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\TimeValue;

/**
 * Class \SimpleSAML\Test\XML\Type\TimeValueTest
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
    #[DataProvider('provideTime')]
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
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideTime(): array
    {
        return [
            'valid' => [true, '21:32:52'],
            'valid time with numerical timezone' => [true, '21:32:52+02:00'],
            'valid time with Zulu timezone' => [true, '19:32:52Z'],
            'valid time with 00:00 timezone' => [true, '19:32:52+00:00'],
            'valid time with sub-seconds' =>  [true, '21:32:52.12679'],
            'invalid negative hour' => [false, '-10:00:00'],
            'invalid hour out of range' => [false, '25:25:10'],
            'invalid invalid format' => [false, '1:20:10'],
        ];
    }
}
