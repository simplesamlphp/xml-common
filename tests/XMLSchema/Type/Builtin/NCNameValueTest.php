<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\NCNameTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Builtin\NCNameValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\Builtin\NCNameValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(NCNameValue::class)]
final class NCNameValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $ncname
     */
    #[DataProvider('provideInvalidNCName')]
    #[DataProvider('provideValidNCName')]
    #[DataProviderExternal(NCNameTest::class, 'provideValidNCName')]
    #[DependsOnClass(NCNameTest::class)]
    public function testNCName(bool $shouldPass, string $ncname): void
    {
        try {
            NCNameValue::fromString($ncname);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function providevalidNCName(): array
    {
        return [
            'whitespace collapse' => [true, "foobar\n"],
            'normalization' => [true, ' foobar '],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidNCName(): array
    {
        return [
            'invalid empty string' => [false, ''],
            'invalid contains wildcard' => [false, 'Te*st'],
            'invalid starts with dash' => [false, '-Test'],
            'invalid starts with digit' => [false, '1Test'],
            'invalid contains colon' => [false, 'Te:st'],
        ];
    }
}
