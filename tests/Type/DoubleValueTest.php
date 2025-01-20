<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\DoubleTest;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\DoubleValue;

/**
 * Class \SimpleSAML\Test\XML\Type\DoubleValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(DoubleValue::class)]
final class DoubleValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $double
     */
    #[DataProvider('provideInvalidDouble')]
    #[DataProvider('provideValidDouble')]
    #[DataProviderExternal(DoubleTest::class, 'provideValidDouble')]
    #[DependsOnClass(DoubleTest::class)]
    public function testDouble(bool $shouldPass, string $double): void
    {
        try {
            DoubleValue::fromString($double);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidDouble(): array
    {
        return [
            'valid with whitespace collapse' => [true, "\v 1234.456 \n"],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidDouble(): array
    {
        return [
            'empty' => [false, ''],
            'case-sensitive NaN' => [false, 'NAN'],
            'invalid +FIN' => [false, '+FIN'],
            'invalid without fractional' => [false, '123'],
        ];
    }
}
