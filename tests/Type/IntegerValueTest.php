<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\IntegerValue;

/**
 * Class \SimpleSAML\Test\XML\Type\IntegerValueTest
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
    #[DataProvider('provideInteger')]
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
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideInteger(): array
    {
        return [
            'empty' => [false, ''],
            'valid integer' => [true, '123456'],
            'valid positive signed' => [true, '+00000012'],
            'valid negative signed' => [true, '-1'],
            'valid with whitespace collapse' => [true, ' 1 234 '],
            'invalid with fractional' => [false, '1234.'],
            'invalid with thousands-delimiter' => [false, '+1,234'],
        ];
    }
}
