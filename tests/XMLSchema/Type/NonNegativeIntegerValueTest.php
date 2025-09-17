<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\DependsOnClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\NonNegativeIntegerTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\NonNegativeIntegerValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\NonNegativeIntegerValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(NonNegativeIntegerValue::class)]
final class NonNegativeIntegerValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $nonNegativeInteger
     */
    #[DataProvider('provideInvalidNonNegativeInteger')]
    #[DataProvider('provideValidNonNegativeInteger')]
    #[DataProviderExternal(NonNegativeIntegerTest::class, 'provideValidNonNegativeInteger')]
    #[DependsOnClass(NonNegativeIntegerTest::class)]
    public function testNonNegativeInteger(bool $shouldPass, string $nonNegativeInteger): void
    {
        try {
            NonNegativeIntegerValue::fromString($nonNegativeInteger);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidNonNegativeInteger(): array
    {
        return [
            'valid with whitespace collapse' => [true, " 1234 \n "],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidNonNegativeInteger(): array
    {
        return [
            'empty' => [false, ''],
            'invalid with fractional' => [false, '1.'],
            'invalid negative' => [false, '-1234'],
            'invalid with thousands-delimiter' => [false, '1,234'],
        ];
    }
}
