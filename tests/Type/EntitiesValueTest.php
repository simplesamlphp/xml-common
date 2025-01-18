<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\EntitiesValue;

/**
 * Class \SimpleSAML\Test\XML\Type\EntitiesValueTest
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
    #[DataProvider('provideEntities')]
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
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideEntities(): array
    {
        return [
            'valid' => [true, 'Snoopy foobar'],
            'diacritical' => [true, 'Snööpy fööbár'],
            'start with colon' => [false, 'foobar :CMS'],
            'start with underscore' => [true, '_1950-10-04 foobar'],
            'invalid first char' => [false, '0836217462 1378943'],
            'empty string' => [false, ''],
            'space' => [true, 'foo bar'],
            'colon' => [false, 'foo:bar'],
            'comma' => [false, 'foo,bar'],
            'whitespace collapse' => [true, "foobar\n"],
            'normalization' => [true, ' foobar '],
        ];
    }


    /**
     * Test the toArray function
     */
    public function testToArray(): void
    {
        $entities = EntitiesValue::fromString("foo \nbar  baz");
        $this->assertEquals(['foo', 'bar', 'baz'], $entities->toArray());
    }
}
