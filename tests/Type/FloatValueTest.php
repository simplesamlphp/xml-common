<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\FloatTest;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\FloatValue;

/**
 * Class \SimpleSAML\Test\XML\Type\FloatValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(FloatValue::class)]
final class FloatValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $float
     */
    #[DataProvider('provideInvalidFloat')]
    #[DataProvider('provideValidFloat')]
    #[DataProviderExternal(FloatTest::class, 'provideValidFloat')]
    #[DependsOnClass(FloatTest::class)]
    public function testFloat(bool $shouldPass, string $float): void
    {
        try {
            FloatValue::fromString($float);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidFloat(): array
    {
        return [
            'valid with whitespace collapse' => [true, " \n1234.456 \n "],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidFloat(): array
    {
        return [
            'empty' => [false, ''],
            'case-sensitive NaN' => [false, 'NAN'],
            'invalid +FIN' => [false, '+FIN'],
            'invalid without fractional' => [false, '123'],
        ];
    }
}
