<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\YearValue;

/**
 * Class \SimpleSAML\Test\XML\Type\YearValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(YearValue::class)]
final class YearValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $year
     */
    #[DataProvider('provideYear')]
    public function testYear(bool $shouldPass, string $year): void
    {
        try {
            YearValue::fromString($year);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
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
            'valid numeric timezone' => [true, '2001+02:00'],
            'valid Zulu timezone' => [true, '2001Z'],
            'whitespace collapse' => [true, ' 2001Z  '],
            'valid 00:00 timezone' => [true, '2001+00:00'],
            '2001 BC' => [true, '-2001'],
            '20000 BC' => [true, '-20000'],
        ];
    }
}
