<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\DependsOnClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\LongTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\LongValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\LongValueTest
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
