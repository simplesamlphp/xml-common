<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\DependsOnClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\UnsignedIntTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\UnsignedIntValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\UnsignedIntValueTest
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
    #[DataProvider('provideInvalidUnsignedInt')]
    #[DataProvider('provideValidUnsignedInt')]
    #[DataProviderExternal(UnsignedIntTest::class, 'provideValidUnsignedInt')]
    #[DependsOnClass(UnsignedIntTest::class)]
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
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidUnsignedInt(): array
    {
        return [
            'valid with whitespace collapse' => [true, " 1234 \n "],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidUnsignedInt(): array
    {
        return [
            'empty' => [false, ''],
            'invalid positive out-of-bounds' => [false, '4294967296'],
            'invalid with fractional' => [false, '1.'],
            'invalid negative' => [false, '-1'],
            'invalid with thousands-delimiter' => [false, '1,234'],
        ];
    }
}
