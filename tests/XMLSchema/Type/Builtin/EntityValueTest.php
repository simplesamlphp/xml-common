<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\EntityTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Builtin\EntityValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\Builtin\EntityValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(EntityValue::class)]
final class EntityValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $entity
     */
    #[DataProvider('provideInvalidEntity')]
    #[DataProvider('provideValidEntity')]
    #[DataProviderExternal(EntityTest::class, 'provideValidEntity')]
    #[DependsOnClass(EntityTest::class)]
    public function testEntity(bool $shouldPass, string $entity): void
    {
        try {
            EntityValue::fromString($entity);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidEntity(): array
    {
        return [
            'whitespace collapse' => [true, "foobar\n"],
            'normalization' => [true, ' foobar '],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidEntity(): array
    {
        return [
            'invalid empty string' => [false, ''],
            'invalid contains wildcard' => [false, 'Te*st'],
            'invalid starts with digit' => [false, '1Test'],
            'invalid contains colon' => [false, 'Te:st'],
        ];
    }
}
