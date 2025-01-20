<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\DurationTest;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\DurationValue;

/**
 * Class \SimpleSAML\Test\XML\Type\DurationValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(DurationValue::class)]
final class DurationValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $duration
     */
    #[DataProvider('provideInvalidDuration')]
    #[DataProvider('provideValidDuration')]
    #[DataProviderExternal(DurationTest::class, 'provideValidDuration')]
    #[DependsOnClass(DurationTest::class)]
    public function testDuration(bool $shouldPass, string $duration): void
    {
        try {
            DurationValue::fromString($duration);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidDuration(): array
    {
        return [
            'whitespace collapse' => [true, "  PT130S \n"],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidDuration(): array
    {
        return [
            'empty' => [false, ''],
            'invalid missing P' => [false, '1Y'],
            'invalid missing T' => [false, 'P1S'],
            'invalid all parts must be positive' => [false, 'P-1Y'],
            'invalid order Y must precede M' => [false, 'P1M2Y'],
            'invalid all parts must me positive' => [false, 'P1Y-1M'],
        ];
    }
}
