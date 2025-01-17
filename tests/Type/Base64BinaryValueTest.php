<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\Base64BinaryValue;

/**
 * Class \SimpleSAML\Test\XML\Type\Base64BinaryValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Base64BinaryValue::class)]
final class Base64BinaryValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $base64
     */
    #[DataProvider('provideBase64')]
    public function testBase64Binary(bool $shouldPass, string $base64): void
    {
        try {
            Base64BinaryValue::fromString($base64);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideBase64(): array
    {
        return [
            'empty' => [false, ''],
            'valid' => [true, 'U2ltcGxlU0FNTHBocA=='],
            'illegal characters' => [false, '&*$(#&^@!(^%$'],
            'length not dividable by 4' => [false, 'U2ltcGxlU0FTHBocA=='],
        ];
    }
}
