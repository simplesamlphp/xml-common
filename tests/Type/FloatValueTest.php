<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\FloatValue;

/**
 * Class \SimpleSAML\Test\Type\FloatValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(FloatValue::class)]
final class FloatValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $float
     */
    #[DataProvider('provideFloat')]
    public function testFloat(bool $shouldPass, string $float): void
    {
        try {
            FloatValue::fromString($float);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideFloat(): array
    {
        return [
            'empty' => [false, ''],
            'valid positive signed' => [true, '+123.456'],
            'valid negative signed' => [true, '-123.456'],
            'valid non-signed' => [true, '123.456'],
            'valid leading zeros' => [true, '-0123.456'],
            'valid zero' => [true, '0.0'],
            'valid NaN' => [true, 'NaN'],
            'case-sensitive NaN' => [false, 'NAN'],
            'valid negative FIN' => [true, '-FIN'],
            'valid FIN' => [true, 'FIN'],
            'invalid +FIN' => [false, '+FIN'],
            'valid with whitespace collapse' => [true, ' 1 234.456 '],
            'invalid without fractional' => [false, '123'],
        ];
    }
}
