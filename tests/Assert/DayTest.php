<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\DayTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class DayTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $day
     */
    #[DataProvider('provideInvalidDay')]
    #[DataProvider('provideValidDay')]
    public function testValidDay(bool $shouldPass, string $day): void
    {
        try {
            Assert::validDay($day);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidDay(): array
    {
        return [
            'valid' => [true, '---01'],
            'valid numeric timezone' => [true, '---01+02:00'],
            'valid Zulu timezone' => [true, '---01Z'],
            'valid 00:00 timezone' => [true, '---01+00:00'],
            'day 15' => [true, '---15'],
            'day 31' => [true, '---31'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidDay(): array
    {
        return [
            'invalid format' => [false, '--30-'],
            'day out of range' => [false, '---35'],
            'missing leading dashes' => [false, '15'],
        ];
    }
}
