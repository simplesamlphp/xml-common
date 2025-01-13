<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\ShortValue;

/**
 * Class \SimpleSAML\Test\Type\ShortValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(ShortValue::class)]
final class ShortValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $short
     */
    #[DataProvider('provideShort')]
    public function testShort(bool $shouldPass, string $short): void
    {
        try {
            ShortValue::fromString($short);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideShort(): array
    {
        return [
            'empty' => [false, ''],
            'valid positive signed' => [true, '+32767'],
            'valid negative signed' => [true, '-32768'],
            'valid non-signed' => [true, '123'],
            'valid leading zeros' => [true, '-0001'],
            'valid zero' => [true, '0'],
            'invalid positive signed out-of-bounds' => [false, '+32768'],
            'invalid negative signed out-of-bounds' => [false, '-32769'],
            'valid with whitespace collapse' => [true, " 1 234 \n"],
            'invalid with fractional' => [false, '1234.'],
            'invalid with thousands-delimiter' => [false, '+1,234'],
        ];
    }
}
