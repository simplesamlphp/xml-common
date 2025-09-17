<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\EntityTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class EntityTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $entity
     */
    #[DataProvider('provideInvalidEntity')]
    #[DataProvider('provideValidEntity')]
    public function testValidEntity(bool $shouldPass, string $entity): void
    {
        try {
            Assert::validEntity($entity);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidEntity(): array
    {
        return [
            'valid' => [true, 'Snoopy'],
            'diacritical' => [true, 'Snööpy'],
            'start with underscore' => [true, '_1950-10-04'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidEntity(): array
    {
        return [
            'start with colon' => [false, ':foobar'],
            'invalid first char' => [false, '0836217462'],
            'empty string' => [false, ''],
            'colon' => [false, 'foo:bar'],
            'comma' => [false, 'foo,bar'],
            'space' => [false, 'foo bar'],
        ];
    }
}
