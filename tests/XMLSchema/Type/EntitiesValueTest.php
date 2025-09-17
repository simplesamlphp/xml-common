<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\DependsOnClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\EntitiesTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\EntitiesValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\EntitiesValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(EntitiesValue::class)]
final class EntitiesValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $entities
     */
    #[DataProvider('provideInvalidEntities')]
    #[DataProvider('provideValidEntities')]
    #[DataProviderExternal(EntitiesTest::class, 'provideValidEntities')]
    #[DependsOnClass(EntitiesTest::class)]
    public function testIDRefs(bool $shouldPass, string $entities): void
    {
        try {
            EntitiesValue::fromString($entities);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * Test the toArray function
     */
    #[DependsOnClass(EntitiesTest::class)]
    public function testToArray(): void
    {
        $entities = EntitiesValue::fromString("foo \nbar  baz");
        $this->assertEquals(['foo', 'bar', 'baz'], $entities->toArray());
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidEntities(): array
    {
        return [
            'whitespace collapse' => [true, "foobar\n"],
            'normalization' => [true, ' foobar '],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidEntities(): array
    {
        return [
            'start with colon' => [false, 'foobar :CMS'],
            'invalid first char' => [false, '0836217462 1378943'],
            'empty string' => [false, ''],
            'colon' => [false, 'foo:bar'],
            'comma' => [false, 'foo,bar'],
        ];
    }
}
