<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\NonPositiveIntegerValue;

/**
 * Class \SimpleSAML\Test\Type\NonPositiveIntegerValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(NonPositiveIntegerValue::class)]
final class NonPositiveIntegerValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $nonPositiveInteger
     */
    #[DataProvider('provideNonPositiveInteger')]
    public function testNonPositiveInteger(bool $shouldPass, string $nonPositiveInteger): void
    {
        try {
            NonPositiveIntegerValue::fromString($nonPositiveInteger);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideNonPositiveInteger(): array
    {
        return [
            'empty' => [false, ''],
            'valid non-positive integer' => [true, '-123456'],
            'valid zero' => [true, '0'],
            'valid negative leading zeros' => [true, '-0000000000000000000005'],
            'valid with whitespace collapse' => [true, " -1 234 \n"],
            'invalid with fractional' => [false, '-1.'],
            'invalid positive' => [false, '1234'],
            'invalid with thousands-delimiter' => [false, '-1,234'],
        ];
    }
}
