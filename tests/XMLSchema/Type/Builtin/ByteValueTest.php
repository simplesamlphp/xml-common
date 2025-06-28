<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\ByteTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Builtin\ByteValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\Builtin\ByteValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(ByteValue::class)]
final class ByteValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $byte
     */
    #[DataProvider('provideInvalidByte')]
    #[DataProvider('provideValidByte')]
    #[DataProviderExternal(ByteTest::class, 'provideValidByte')]
    #[DependsOnClass(ByteTest::class)]
    public function testByte(bool $shouldPass, string $byte): void
    {
        try {
            ByteValue::fromString($byte);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidByte(): array
    {
        return [
            'valid with whitespace collapse' => [true, "\t 123 \n"],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidByte(): array
    {
        return [
            'empty' => [false, ''],
            'invalid positive signed out-of-bounds' => [false, '+128'],
            'invalid negative signed out-of-bounds' => [false, '-129'],
            'invalid with fractional' => [false, '123.'],
        ];
    }
}
