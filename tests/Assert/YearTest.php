<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
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
    #[DataProvider('provideYear')]
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
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideYear(): array
    {
        return [
            'empty' => [false, ''],
            'valid' => [true, '2001'],
            'whitespace' => [false, ' 2001 '],
            'valid numeric timezone' => [true, '2001+02:00'],
            'valid Zulu timezone' => [true, '2001Z'],
            'valid 00:00 timezone' => [true, '2001+00:00'],
            '2001 BC' => [true, '-2001'],
            '20000 BC' => [true, '-20000'],
        ];
    }
}
