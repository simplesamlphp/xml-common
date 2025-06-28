<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\ShortTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Builtin\ShortValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\Builtin\ShortValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(ShortValue::class)]
final class ShortValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $short
     */
    #[DataProvider('provideInvalidShort')]
    #[DataProvider('provideValidShort')]
    #[DataProviderExternal(ShortTest::class, 'provideValidShort')]
    #[DependsOnClass(ShortTest::class)]
    public function testShort(bool $shouldPass, string $short): void
    {
        try {
            ShortValue::fromString($short);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidShort(): array
    {
        return [
            'valid with whitespace collapse' => [true, " 1234 \n "],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidShort(): array
    {
        return [
            'empty' => [false, ''],
            'invalid positive signed out-of-bounds' => [false, '+32768'],
            'invalid negative signed out-of-bounds' => [false, '-32769'],
            'invalid with fractional' => [false, '1234.'],
            'invalid with thousands-delimiter' => [false, '+1,234'],
        ];
    }
}
