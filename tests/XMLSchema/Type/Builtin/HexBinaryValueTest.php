<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\HexBinaryTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Builtin\HexBinaryValue;

/**
 * Class \SimpleSAML\Test\XML\Type\Builtin\HexBinaryValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(HexBinaryValue::class)]
final class HexBinaryValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $hexbin
     */
    #[DataProvider('provideInvalidHexBinary')]
    #[DataProvider('provideValidHexBinary')]
    #[DataProviderExternal(HexBinaryTest::class, 'provideValidHexBinary')]
    #[DependsOnClass(HexBinaryTest::class)]
    public function testHexBinary(bool $shouldPass, string $hexbin): void
    {
        try {
            HexBinaryValue::fromString($hexbin);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidHexBinary(): array
    {
        return [];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidHexBinary(): array
    {
        return [
            'empty' => [false, ''],
            'base64' => [false, 'U2ltcGxlU0FNTHBocA=='],
            'invalid' => [false, '3f3r'],
            'bogus' => [false, '&*$(#&^@!(^%$'],
            'length not dividable by 4' => [false, '3f3'],
        ];
    }
}
