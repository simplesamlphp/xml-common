<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\TimeTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class TimeTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $time
     */
    #[DataProvider('provideInvalidTime')]
    #[DataProvider('provideValidTime')]
    public function testValidTime(bool $shouldPass, string $time): void
    {
        try {
            Assert::validTime($time);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidTime(): array
    {
        return [
            'valid' => [true, '21:32:52'],
            'valid time with numerical timezone' => [true, '21:32:52+02:00'],
            'valid time with Zulu timezone' => [true, '19:32:52Z'],
            'valid time with 00:00 timezone' => [true, '19:32:52+00:00'],
            'valid time with sub-seconds' =>  [true, '21:32:52.12679'],
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
