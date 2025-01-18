<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
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
    #[DataProvider('provideEntities')]
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
     * @return array<int, array{0: bool, 1: string}>
     */
    public static function provideEntities(): array
    {
        return [
            [true, 'Snoopy'],
            [true, 'CMS'],
            [true, 'fööbár'],
            [false, '-1950-10-04'],
            [false, '0836217462 0836217463'],
            [true, 'foo bar'],
            // Quotes are forbidden
            [false, 'foo "bar" baz'],
            // Commas are forbidden
            [false, 'foo,bar'],
            // Trailing newlines are forbidden
            [false, "foobar\n"],
        ];
    }
}
