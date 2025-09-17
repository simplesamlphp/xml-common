<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\NMTokensTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class NMTokensTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $nmtokens
     */
    #[DataProvider('provideInvalidNMTokens')]
    #[DataProvider('provideValidNMTokens')]
    public function testValidTokens(bool $shouldPass, string $nmtokens): void
    {
        try {
            Assert::validNMTokens($nmtokens);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidNMTokens(): array
    {
        return [
            'valid' => [true, 'Snoopy foobar'],
            'diacritical' => [true, 'Snoopy fööbár'],
            'start with colon' => [true, ':CMS :ABC'],
            'start with dash' => [true, '-1950-10-04 -1984-11-07'],
            'numeric first char' => [true, '0836217462'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidNMTokens(): array
    {
        return [
            'comma' => [false, 'foo,bar'],
            'quotes' => [false, 'foo "bar" baz'],
            'trailing newline' => [false, "foobar\n"],
        ];
    }
}
