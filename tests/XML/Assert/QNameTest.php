<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\QNameTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class QNameTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $name
     */
    #[DataProvider('provideInvalidQName')]
    #[DataProvider('provideValidQName')]
    public function testValidQName(bool $shouldPass, string $name): void
    {
        try {
            Assert::validQName($name);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidQName(): array
    {
        return [
            'valid' => [true, 'some:Test'],
            // both parts can contain a dash
            '1st part containing dash' => [true, 'som-e:Test'],
            '2nd part containing dash' => [true, 'some:T-est'],
            'both parts containing dash' => [true, 'so-me:T-est'],
            // A single NCName is also a valid QName
            'no colon' => [true, 'Test'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidQName(): array
    {
        return [
            'start 2nd part with dash' => [false, 'some:-Test'],
            'start both parts with dash' => [false, '-some:-Test'],
            'start with colon' => [false, ':test'],
            'multiple colons' => [false, 'test:test:test'],
            'start with digit' => [false, '1Test'],
            'wildcard' => [false, 'Te*st'],
            // Trailing newlines are forbidden
            'trailing newline' => [false, "some:Test\n"],
        ];
    }
}
