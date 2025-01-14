<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\UnsignedIntValue;

/**
 * Class \SimpleSAML\Test\Type\UnsignedIntValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(UnsignedIntValue::class)]
final class UnsignedIntValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $unsignedInt
     */
    #[DataProvider('provideUnsignedInt')]
    public function testUnsignedInt(bool $shouldPass, string $unsignedInt): void
    {
        try {
            UnsignedIntValue::fromString($unsignedInt);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideUnsignedInt(): array
    {
        return [
            'empty' => [false, ''],
            'valid positive integer' => [true, '4294967295'],
            'invalid positive out-of-bounds' => [false, '4294967296'],
            'valid signed positive integer' => [true, '+4294967295'],
            'valid zero' => [true, '0'],
            'valid negative leading zeros' => [true, '0000000000000000000005'],
            'invalid with fractional' => [false, '1.'],
            'valid with whitespace collapse' => [true, " 1 234 \n"],
            'invalid negative' => [false, '-1'],
            'invalid with thousands-delimiter' => [false, '1,234'],
        ];
    }
}
