<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\NegativeIntegerTest;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\NegativeIntegerValue;

/**
 * Class \SimpleSAML\Test\XML\Type\NegativeIntegerValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(NegativeIntegerValue::class)]
final class NegativeIntegerValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $negativeInteger
     */
    #[DataProvider('provideInvalidNegativeInteger')]
    #[DataProvider('provideValidNegativeInteger')]
    #[DataProviderExternal(NegativeIntegerTest::class, 'provideValidNegativeInteger')]
    #[DependsOnClass(NegativeIntegerTest::class)]
    public function testNegativeInteger(bool $shouldPass, string $negativeInteger): void
    {
        try {
            NegativeIntegerValue::fromString($negativeInteger);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidNegativeInteger(): array
    {
        return [
            'valid with whitespace collapse' => [true, "\t -1234 \n "],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidNegativeInteger(): array
    {
        return [
            'empty' => [false, ''],
            'invalid zero' => [false, '0'],
            'invalid with fractional' => [false, '-1.'],
            'invalid positive' => [false, '1234'],
            'invalid with thousands-delimiter' => [false, '-1,234'],
        ];
    }
}
