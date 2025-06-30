<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\IntTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\IntValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\IntValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(IntValue::class)]
final class IntValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $int
     */
    #[DataProvider('provideInvalidInt')]
    #[DataProvider('provideValidInt')]
    #[DataProviderExternal(IntTest::class, 'provideValidInt')]
    #[DependsOnClass(IntTest::class)]
    public function testInt(bool $shouldPass, string $int): void
    {
        try {
            IntValue::fromString($int);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidInt(): array
    {
        return [
            'valid with whitespace collapse' => [true, "\v 1234 \n"],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidInt(): array
    {
        return [
            'empty' => [false, ''],
            'invalid positive signed out-of-bounds' => [false, '+2147483648'],
            'invalid negative signed out-of-bounds' => [false, '-2147483649'],
            'invalid with fractional' => [false, '1234.'],
            'invalid with thousands-delimiter' => [false, '+1,234'],
        ];
    }
}
