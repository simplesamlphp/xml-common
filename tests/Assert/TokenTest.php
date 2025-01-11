<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\TokenTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class TokenTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $token
     */
    #[DataProvider('provideToken')]
    public function testValidToken(bool $shouldPass, string $token): void
    {
        try {
            Assert::validToken($token);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<int, array{0: bool, 1: string}>
     */
    public static function provideToken(): array
    {
        return [
            [true, 'Snoopy'],
            [true, ':CMS'],
            [true, 'fööbár'],
            [true, '-1950-10-04'],
            [true, ''],
            [true, '0836217462'],
            [true, 'foo bar'],
            [true, 'foo,bar'],
            [true, "foobar\n"],
        ];
    }
}
