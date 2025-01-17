<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
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
    #[DataProvider('providePositiveInteger')]
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
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function providePositiveInteger(): array
    {
        return [
            'empty' => [false, ''],
            'valid positive integer' => [true, '123456'],
            'valid signed positive integer' => [true, '+123456'],
            'invalid zero' => [false, '0'],
            'valid negative leading zeros' => [true, '0000000000000000000005'],
            'valid with whitespace collapse' => [true, " 1 234 \n"],
            'invalid with fractional' => [false, '1.'],
            'invalid negative' => [false, '-1234'],
            'invalid with thousands-delimiter' => [false, '1,234'],
        ];
    }
}
