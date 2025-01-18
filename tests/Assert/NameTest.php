<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\NameTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class NameTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $name
     */
    #[DataProvider('provideName')]
    public function testValidToken(bool $shouldPass, string $name): void
    {
        try {
            Assert::validName($name);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<int, array{0: bool, 1: string}>
     */
    public static function provideName(): array
    {
        return [
            [true, 'Snoopy'],
            [true, ':CMS'],
            [true, 'fööbár'],
            [true, '-1950-10-04'],
            // The empty string is not valid
            [false, ''],
            // Must start with a letter, a dash or a colon
            [false, '0836217462'],
            // Spaces are forbidden
            [false, 'foo bar'],
            // Commas are forbidden
            [false, 'foo,bar'],
            // Trailing newlines are forbidden
            [false, "foobar\n"],
        ];
    }
}
