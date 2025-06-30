<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\IntegerTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\IntegerValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\IntegerValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(IntegerValue::class)]
final class IntegerValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $integer
     */
    #[DataProvider('provideInvalidInteger')]
    #[DataProvider('provideValidInteger')]
    #[DataProviderExternal(IntegerTest::class, 'provideValidInteger')]
    #[DependsOnClass(IntegerTest::class)]
    public function testInteger(bool $shouldPass, string $integer): void
    {
        try {
            IntegerValue::fromString($integer);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function providevalidInteger(): array
    {
        return [
            'valid with whitespace collapse' => [true, " \n1234\r "],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidInteger(): array
    {
        return [
            'empty' => [false, ''],
            'invalid with fractional' => [false, '1234.'],
            'invalid with thousands-delimiter' => [false, '+1,234'],
        ];
    }
}
