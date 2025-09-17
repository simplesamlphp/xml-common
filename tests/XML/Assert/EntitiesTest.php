<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\EntitiesTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class EntitiesTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $entities
     */
    #[DataProvider('provideInvalidEntities')]
    #[DataProvider('provideValidEntities')]
    public function testValidEntities(bool $shouldPass, string $entities): void
    {
        try {
            Assert::validEntities($entities);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidEntities(): array
    {
        return [
            'valid' => [true, 'Snoopy foobar'],
            'diacritical' => [true, 'Snööpy fööbár'],
            'start with underscore' => [true, '_1950-10-04 foobar'],
            'space' => [true, 'foo bar'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidEntities(): array
    {
        return [
            'start with colon' => [false, ':foobar :CMS'],
            'invalid first char' => [false, '0836217462 1378943'],
            'empty string' => [false, ''],
            'colon' => [false, 'foo:bar'],
            'comma' => [false, 'foo,bar'],
        ];
    }
}
