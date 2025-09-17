<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\NMTokenTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class NMTokenTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $nmtoken
     */
    #[DataProvider('provideInvalidNMToken')]
    #[DataProvider('provideValidNMToken')]
    public function testValidToken(bool $shouldPass, string $nmtoken): void
    {
        try {
            Assert::validNMToken($nmtoken);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidNMToken(): array
    {
        return [
            'valid' => [true, 'Snoopy'],
            'diacritical' => [true, 'fööbár'],
            'start with colon' => [true, ':CMS'],
            'start with dash' => [true, '-1950-10-04'],
            'numeric first char' => [true, '0836217462'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidNMToken(): array
    {
        return [
            'space' => [false, 'foo bar'],
            'comma' => [false, 'foo,bar'],
            'trailing newline' => [false, "foobar\n"],
        ];
    }
}
