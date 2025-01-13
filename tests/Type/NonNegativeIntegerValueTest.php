<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\NonNegativeIntegerValue;

/**
 * Class \SimpleSAML\Test\Type\NonNegativeIntegerValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(NonNegativeIntegerValue::class)]
final class NonNegativeIntegerValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $nonNegativeInteger
     */
    #[DataProvider('provideNonNegativeInteger')]
    public function testNonNegativeInteger(bool $shouldPass, string $nonNegativeInteger): void
    {
        try {
            NonNegativeIntegerValue::fromString($nonNegativeInteger);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideNonNegativeInteger(): array
    {
        return [
            'empty' => [false, ''],
            'valid positive integer' => [true, '123456'],
            'valid signed positive integer' => [true, '+123456'],
            'valid zero' => [true, '0'],
            'valid negative leading zeros' => [true, '0000000000000000000005'],
            'valid with whitespace collapse' => [true, " 1 234 \n"],
            'invalid with fractional' => [false, '1.'],
            'invalid negative' => [false, '-1234'],
            'invalid with thousands-delimiter' => [false, '1,234'],
        ];
    }
}
