<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\UnsignedShortTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Builtin\UnsignedShortValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\Builtin\UnsignedShortValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(UnsignedShortValue::class)]
final class UnsignedShortValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $unsignedShort
     */
    #[DataProvider('provideInvalidUnsignedShort')]
    #[DataProvider('provideValidUnsignedShort')]
    #[DataProviderExternal(UnsignedShortTest::class, 'provideValidUnsignedShort')]
    #[DependsOnClass(UnsignedShortTest::class)]
    public function testUnsignedShort(bool $shouldPass, string $unsignedShort): void
    {
        try {
            UnsignedShortValue::fromString($unsignedShort);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidUnsignedShort(): array
    {
        return [
            'valid with whitespace collapse' => [true, " 1234 \f "],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidUnsignedShort(): array
    {
        return [
            'empty' => [false, ''],
            'invalid positive out-of-bounds' => [false, '65536'],
            'invalid with fractional' => [false, '1.'],
            'invalid negative' => [false, '-1'],
            'invalid with thousands-delimiter' => [false, '1,234'],
        ];
    }
}
