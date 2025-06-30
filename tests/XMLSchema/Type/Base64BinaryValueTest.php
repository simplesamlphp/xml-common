<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\Base64BinaryTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Base64BinaryValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\Base64BinaryValueTest
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
    #[DataProvider('provideInvalidBase64')]
    #[DataProvider('provideValidBase64')]
    #[DataProviderExternal(Base64BinaryTest::class, 'provideValidBase64')]
    #[DependsOnClass(Base64BinaryTest::class)]
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
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidBase64(): array
    {
        return [
            'whitespace ignored' => [true, "U2ltcGxl\n U0FNTHBocA=="],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidBase64(): array
    {
        return [
            'empty' => [false, ''],
            'illegal characters' => [false, '&*$(#&^@!(^%$'],
            'length not dividable by 4' => [false, 'U2ltcGxlU0FTHBocA=='],
        ];
    }
}
