<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\YearTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class YearTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $year
     */
    #[DataProvider('provideInvalidYear')]
    #[DataProvider('provideValidYear')]
    public function testValidYear(bool $shouldPass, string $year): void
    {
        try {
            Assert::validYear($year);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidYear(): array
    {
        return [
            'valid' => [true, '2001'],
            'valid numeric timezone' => [true, '2001+02:00'],
            'valid Zulu timezone' => [true, '2001Z'],
            'valid 00:00 timezone' => [true, '2001+00:00'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidYear(): array
    {
        return [
            'empty' => [false, ''],
            'whitespace' => [false, ' 2001 '],
        ];
    }
}
