<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\UnsignedLongTest;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\UnsignedLongValue;

/**
 * Class \SimpleSAML\Test\XML\Type\UnsignedLongValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(UnsignedLongValue::class)]
final class UnsignedLongValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $unsignedLong
     */
    #[DataProvider('provideInvalidUnsignedLong')]
    #[DataProvider('provideValidUnsignedLong')]
    #[DataProviderExternal(UnsignedLongTest::class, 'provideValidUnsignedLong')]
    #[DependsOnClass(UnsignedLongTest::class)]
    public function testUnsignedLong(bool $shouldPass, string $unsignedLong): void
    {
        try {
            UnsignedLongValue::fromString($unsignedLong);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidUnsignedLong(): array
    {
        return [
            'valid with whitespace collapse' => [true, " 1234 \t "],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidUnsignedLong(): array
    {
        return [
            'empty' => [false, ''],
            'invalid positive out-of-bounds' => [false, '18446744073709551616'],
            'invalid with fractional' => [false, '1.'],
            'invalid negative' => [false, '-1'],
            'invalid with thousands-delimiter' => [false, '1,234'],
        ];
    }
}
