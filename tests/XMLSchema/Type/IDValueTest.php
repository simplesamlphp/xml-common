<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\DependsOnClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\IDTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\IDValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\IDValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(IDValue::class)]
final class IDValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $id
     */
    #[DataProvider('provideInvalidID')]
    #[DataProvider('provideValidID')]
    #[DataProviderExternal(IDTest::class, 'provideValidID')]
    #[DependsOnClass(IDTest::class)]
    public function testID(bool $shouldPass, string $id): void
    {
        try {
            IDValue::fromString($id);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidID(): array
    {
        return [
            'whitespace collapse' => [true, "foobar\n"],
            'normalization' => [true, ' foobar '],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidID(): array
    {
        return [
            'invalid empty string' => [false, ''],
            'invalid contains wildcard' => [false, 'Te*st'],
            'invalid starts with digit' => [false, '1Test'],
            'invalid contains colon' => [false, 'Te:st'],
        ];
    }
}
