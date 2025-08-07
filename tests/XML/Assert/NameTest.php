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
    #[DataProvider('provideInvalidName')]
    #[DataProvider('provideValidName')]
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
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidName(): array
    {
        return [
            'valid' => [true, 'Snoopy'],
            'diacritical' => [true, 'fööbár'],
            'urn' => [true, 'urn:myAttributeName'],
            'start with colon' => [true, ':CMS'],
            'start with dash' => [true, '-1950-10-04'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidName(): array
    {
        return [
            'invalid first char' => [false, '0836217462'],
            'empty string' => [false, ''],
            'space' => [false, 'foo bar'],
            'comma' => [false, 'foo,bar'],
        ];
    }
}
