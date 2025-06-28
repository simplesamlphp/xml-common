<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\NameTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Builtin\NameValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\Builtin\NameValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(NameValue::class)]
final class NameValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $name
     */
    #[DataProvider('provideInvalidName')]
    #[DataProvider('provideValidName')]
    #[DataProviderExternal(NameTest::class, 'provideValidName')]
    #[DependsOnClass(NameTest::class)]
    public function testName(bool $shouldPass, string $name): void
    {
        try {
            NameValue::fromString($name);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidName(): array
    {
        return [
            'whitespace collapse' => [true, "foobar\n"],
            'normalization' => [true, ' foobar '],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidName(): array
    {
        return [
            'invalid first char' => [false, '0836217462'],
            'empty string' => [false, ''],
            'space' => [false, 'foo bar'],
            'comma' => [false, 'foo,bar'],
        ];
    }
}
