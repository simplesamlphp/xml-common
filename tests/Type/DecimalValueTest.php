<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\DecimalTest;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\DecimalValue;

/**
 * Class \SimpleSAML\Test\XML\Type\DecimalValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(DecimalValue::class)]
final class DecimalValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $decimal
     */
    #[DataProvider('provideInvalidDecimal')]
    #[DataProvider('provideValidDecimal')]
    #[DataProviderExternal(DecimalTest::class, 'provideValidDecimal')]
    #[DependsOnClass(DecimalTest::class)]
    public function testDecimal(bool $shouldPass, string $decimal): void
    {
        try {
            DecimalValue::fromString($decimal);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidDecimal(): array
    {
        return [
            'valid with whitespace collapse' => [true, "\v 1234.456 \t "],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidDecimal(): array
    {
        return [
            'empty' => [false, ''],
            'invalid scientific notation' => [false, '1234.456E+2'],
            'invalid with thousands-delimiter' => [false, '+1,234.456'],
        ];
    }
}
