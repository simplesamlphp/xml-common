<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\PositiveIntegerTest;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\PositiveIntegerValue;

/**
 * Class \SimpleSAML\Test\XML\Type\PositiveIntegerValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(PositiveIntegerValue::class)]
final class PositiveIntegerValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $positiveInteger
     */
    #[DataProvider('provideInvalidPositiveInteger')]
    #[DataProvider('provideValidPositiveInteger')]
    #[DataProviderExternal(PositiveIntegerTest::class, 'provideValidPositiveInteger')]
    #[DependsOnClass(PositiveIntegerTest::class)]
    public function testPositiveInteger(bool $shouldPass, string $positiveInteger): void
    {
        try {
            PositiveIntegerValue::fromString($positiveInteger);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidPositiveInteger(): array
    {
        return [
            'valid with whitespace collapse' => [true, " 1234 \n "],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidPositiveInteger(): array
    {
        return [
            'empty' => [false, ''],
            'invalid zero' => [false, '0'],
            'invalid with fractional' => [false, '1.'],
            'invalid negative' => [false, '-1234'],
            'invalid with thousands-delimiter' => [false, '1,234'],
        ];
    }
}
