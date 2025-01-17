<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\NMTokenValue;

/**
 * Class \SimpleSAML\Test\XML\Type\NMTokenValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(NMTokenValue::class)]
final class NMTokenValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $nmtoken
     */
    #[DataProvider('provideNMToken')]
    public function testName(bool $shouldPass, string $nmtoken): void
    {
        try {
            NMTokenValue::fromString($nmtoken);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideNMToken(): array
    {
        return [
            'valid' => [true, 'Snoopy'],
            'diacritical' => [true, 'fööbár'],
            'start with colon' => [true, ':CMS'],
            'start with dash' => [true, '-1950-10-04'],
            'numeric first char' => [true, '0836217462'],
            'space' => [false, 'foo bar'],
            'comma' => [false, 'foo,bar'],
            'whitespace collapse' => [true, "foobar\n"],
            'normalization' => [true, ' foobar '],
        ];
    }
}
