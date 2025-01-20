<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\LongTest;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\LongValue;

/**
 * Class \SimpleSAML\Test\XML\Type\LongValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(LongValue::class)]
final class LongValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $long
     */
    #[DataProvider('provideInvalidLong')]
    #[DataProvider('provideValidLong')]
    #[DataProviderExternal(LongTest::class, 'provideValidLong')]
    #[DependsOnClass(LongTest::class)]
    public function testLong(bool $shouldPass, string $long): void
    {
        try {
            LongValue::fromString($long);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidLong(): array
    {
        return [
            'valid with whitespace collapse' => [true, " 1234 \n "],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidLong(): array
    {
        return [
            'empty' => [false, ''],
            'invalid positive signed out-of-bounds' => [false, '+9223372036854775808'],
            'invalid negative signed out-of-bounds' => [false, '-9223372036854775809'],
            'invalid with fractional' => [false, '1234.'],
            'invalid with thousands-delimiter' => [false, '+1,234'],
        ];
    }
}
