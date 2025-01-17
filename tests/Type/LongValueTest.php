<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
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
    #[DataProvider('provideLong')]
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
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideLong(): array
    {
        return [
            'empty' => [false, ''],
            'valid positive signed' => [true, '+9223372036854775807'],
            'valid negative signed' => [true, '-9223372036854775808'],
            'valid non-signed' => [true, '9223372036854775807'],
            'valid leading zeros' => [true, '-0001'],
            'valid zero' => [true, '0'],
            'valid with whitespace collapse' => [true, " 1 234 \n"],
            'invalid positive signed out-of-bounds' => [false, '+9223372036854775808'],
            'invalid negative signed out-of-bounds' => [false, '-9223372036854775809'],
            'invalid with fractional' => [false, '1234.'],
            'invalid with thousands-delimiter' => [false, '+1,234'],
        ];
    }
}
