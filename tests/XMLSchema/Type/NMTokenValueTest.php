<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\DependsOnClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\NMTokenTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\NMTokenValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\NMTokenValueTest
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
