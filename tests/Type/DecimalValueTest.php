<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
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
    #[DataProvider('provideDecimal')]
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
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideDecimal(): array
    {
        return [
            'empty' => [false, ''],
            'valid decimal' => [true, '123.456'],
            'valid positive signed' => [true, '+123.456'],
            'valid negative signed' => [true, '-123.456'],
            'valid fractional only' => [true, '-.456'],
            'valid without fraction' => [true, '-456'],
            'valid with whitespace collapse' => [true, ' 1 234.456 '],
            'invalid scientific notation' => [false, '1234.456E+2'],
            'invalid with thousands-delimiter' => [false, '+1,234.456'],
        ];
    }
}
