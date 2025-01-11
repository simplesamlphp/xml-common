<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\DateTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class DateTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $date
     */
    #[DataProvider('provideDate')]
    public function testValidDate(bool $shouldPass, string $date): void
    {
        try {
            Assert::validDate($date);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
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
            'missing part' => [false, '2001-10'],
            'day out of range' => [false, '2001-10-32'],
            'month out of range' => [false, '2001-13-26+02:00'],
            'century part missing' => [false, '01-10-26'],
        ];
    }
}
