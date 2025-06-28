<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\IDRefTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Builtin\IDRefValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\Builtin\IDRefValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(IDRefValue::class)]
final class IDRefValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $idref
     */
    #[DataProvider('provideInvalidIDRef')]
    #[DataProvider('provideValidIDRef')]
    #[DataProviderExternal(IDRefTest::class, 'provideValidIDRef')]
    #[DependsOnClass(IDRefTest::class)]
    public function testIDRef(bool $shouldPass, string $idref): void
    {
        try {
            IDRefValue::fromString($idref);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidIDRef(): array
    {
        return [
            'whitespace collapse' => [true, "foobar\n"],
            'normalization' => [true, ' foobar '],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidIDRef(): array
    {
        return [
            'invalid empty string' => [false, ''],
            'invalid contains wildcard' => [false, 'Te*st'],
            'invalid starts with digit' => [false, '1Test'],
            'invalid contains colon' => [false, 'Te:st'],
        ];
    }
}
