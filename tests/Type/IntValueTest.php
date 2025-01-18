<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\IntValue;

/**
 * Class \SimpleSAML\Test\XML\Type\IntValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(IntValue::class)]
final class IntValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $int
     */
    #[DataProvider('provideInt')]
    public function testInt(bool $shouldPass, string $int): void
    {
        try {
            IntValue::fromString($int);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideInt(): array
    {
        return [
            'empty' => [false, ''],
            'valid positive signed' => [true, '+2147483647'],
            'valid negative signed' => [true, '-2147483648'],
            'valid non-signed' => [true, '123'],
            'valid leading zeros' => [true, '-0001'],
            'valid zero' => [true, '0'],
            'invalid positive signed out-of-bounds' => [false, '+2147483648'],
            'invalid negative signed out-of-bounds' => [false, '-2147483649'],
            'valid with whitespace collapse' => [true, " 1 234 \n"],
            'invalid with fractional' => [false, '1234.'],
            'invalid with thousands-delimiter' => [false, '+1,234'],
        ];
    }
}
