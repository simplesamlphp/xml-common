<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\DateTimeTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class DateTimeTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $dateTime
     */
    #[DataProvider('provideDateTime')]
    public function testValidDateTime(bool $shouldPass, string $dateTime): void
    {
        try {
            Assert::validDateTime($dateTime);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideDateTime(): array
    {
        return [
            'valid' => [true, '2001-10-26T21:32:52'],
            'valid with numeric difference' => [true, '2001-10-26T21:32:52+02:00'],
            'valid with Zulu' => [true, '2001-10-26T19:32:52Z'],
            'valid with 00:00 difference' => [true, '2001-10-26T19:32:52+00:00'],
            'valid with negative value' => [true, '-2001-10-26T21:32:52'],
            'valid with subseconds' => [true, '2001-10-26T21:32:52.12679'],
            'valid with more than four digit year' => [true, '-22001-10-26T21:32:52+02:00'],
            'valid with sub-seconds' => [true, '2001-10-26T21:32:52.12679'],
            'missing time' => [false, '2001-10-26'],
            'missing second' => [false, '2001-10-26T21:32'],
            'hour out of range' => [false, '2001-10-26T25:32:52+02:00'],
            'year 0000' => [false, '0000-10-26T25:32:52+02:00'],
            'prefixed zero' => [false, '02001-10-26T25:32:52+02:00'],
            'wrong format' => [false, '01-10-26T21:32'],
        ];
    }
}
