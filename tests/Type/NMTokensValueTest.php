<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\NMTokensValue;

/**
 * Class \SimpleSAML\Test\XML\Type\NMTokensValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(NMTokensValue::class)]
final class NMTokensValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $nmtokens
     */
    #[DataProvider('provideNMTokens')]
    public function testNMtokens(bool $shouldPass, string $nmtokens): void
    {
        try {
            NMTokensValue::fromString($nmtokens);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideNMTokens(): array
    {
        return [
            'valid' => [true, 'Snoopy foobar'],
            'diacritical' => [true, 'Snoopy fööbár'],
            'start with colon' => [true, ':CMS :ABC'],
            'start with dash' => [true, '-1950-10-04 -1984-11-07'],
            'numeric first char' => [true, '0836217462'],
            'comma' => [false, 'foo,bar'],
            'whitespace collapse' => [true, "foobar\n"],
            'normalization' => [true, ' foobar   nmtoken '],
        ];
    }


    /**
     * Test the toArray function
     */
    public function testToArray(): void
    {
        $nmtokens = NMTokensValue::fromString("foo \nbar  baz");
        $this->assertEquals(['foo', 'bar', 'baz'], $nmtokens->toArray());
    }
}
