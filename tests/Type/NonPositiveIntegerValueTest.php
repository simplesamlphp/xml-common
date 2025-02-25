<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\NonPositiveIntegerTest;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\NonPositiveIntegerValue;

/**
 * Class \SimpleSAML\Test\XML\Type\NonPositiveIntegerValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(NonPositiveIntegerValue::class)]
final class NonPositiveIntegerValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $nonPositiveInteger
     */
    #[DataProvider('provideInvalidNonPositiveInteger')]
    #[DataProvider('provideValidNonPositiveInteger')]
    #[DataProviderExternal(NonPositiveIntegerTest::class, 'provideValidNonPositiveInteger')]
    #[DependsOnClass(NonPositiveIntegerTest::class)]
    public function testNonPositiveInteger(bool $shouldPass, string $nonPositiveInteger): void
    {
        try {
            NonPositiveIntegerValue::fromString($nonPositiveInteger);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidNonPositiveInteger(): array
    {
        return [
            'valid with whitespace collapse' => [true, " -1234 \n "],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidNonPositiveInteger(): array
    {
        return [
            'empty' => [false, ''],
            'invalid with fractional' => [false, '-1.'],
            'invalid positive' => [false, '1234'],
            'invalid with thousands-delimiter' => [false, '-1,234'],
        ];
    }
}
