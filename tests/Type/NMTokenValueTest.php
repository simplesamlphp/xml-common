<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\NMTokenTest;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\NMTokenValue;

/**
 * Class \SimpleSAML\Test\XML\Type\NMTokenValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(NMTokenValue::class)]
final class NMTokenValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $nmtoken
     */
    #[DataProvider('provideInvalidNMToken')]
    #[DataProvider('provideValidNMToken')]
    #[DataProviderExternal(NMTokenTest::class, 'provideValidNMToken')]
    #[DependsOnClass(NMTokenTest::class)]
    public function testNMToken(bool $shouldPass, string $nmtoken): void
    {
        try {
            NMTokenValue::fromString($nmtoken);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidNMToken(): array
    {
        return [
            'whitespace collapse' => [true, "foobar\n"],
            'normalization' => [true, ' foobar '],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidNMToken(): array
    {
        return [
            'space' => [false, 'foo bar'],
            'comma' => [false, 'foo,bar'],
        ];
    }
}
