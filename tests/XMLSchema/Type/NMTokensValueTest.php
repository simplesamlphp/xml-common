<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\NMTokensTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\NMTokensValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\NMTokensValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(NMTokensValue::class)]
final class NMTokensValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $nmtokens
     */
    #[DataProvider('provideInvalidNMTokens')]
    #[DataProvider('provideValidNMTokens')]
    #[DataProviderExternal(NMTokensTest::class, 'provideValidNMTokens')]
    #[DependsOnClass(NMTokensTest::class)]
    public function testNMtokens(bool $shouldPass, string $nmtokens): void
    {
        try {
            NMTokensValue::fromString($nmtokens);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * Test the toArray function
     */
    #[DependsOnClass(NMTokensTest::class)]
    public function testToArray(): void
    {
        $nmtokens = NMTokensValue::fromString("foo \nbar  baz");
        $this->assertEquals(['foo', 'bar', 'baz'], $nmtokens->toArray());
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidNMTokens(): array
    {
        return [
            'whitespace collapse' => [true, "foobar\n"],
            'normalization' => [true, ' foobar   nmtoken '],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidNMTokens(): array
    {
        return [
            'comma' => [false, 'foo,bar'],
        ];
    }
}
