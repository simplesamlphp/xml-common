<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\DependsOnClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\UnsignedByteTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\UnsignedByteValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\UnsignedByteValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(UnsignedByteValue::class)]
final class UnsignedByteValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $unsignedByte
     */
    #[DataProvider('provideInvalidUnsignedByte')]
    #[DataProvider('provideValidUnsignedByte')]
    #[DataProviderExternal(UnsignedByteTest::class, 'provideValidUnsignedByte')]
    #[DependsOnClass(UnsignedByteTest::class)]
    public function testUnsignedByte(bool $shouldPass, string $unsignedByte): void
    {
        try {
            UnsignedByteValue::fromString($unsignedByte);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidUnsignedByte(): array
    {
        return [
            'valid with whitespace collapse' => [true, " 124 \n "],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidUnsignedByte(): array
    {
        return [
            'empty' => [false, ''],
            'invalid positive out-of-bounds' => [false, '256'],
            'invalid with fractional' => [false, '1.'],
            'invalid negative' => [false, '-1'],
            'invalid with thousands-delimiter' => [false, '1,23'],
        ];
    }
}
