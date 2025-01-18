<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\NameValue;

/**
 * Class \SimpleSAML\Test\XML\Type\NameValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(NameValue::class)]
final class NameValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $name
     */
    #[DataProvider('provideName')]
    public function testName(bool $shouldPass, string $name): void
    {
        try {
            NameValue::fromString($name);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideName(): array
    {
        return [
            'valid' => [true, 'Snoopy'],
            'diacritical' => [true, 'fööbár'],
            'start with colon' => [true, ':CMS'],
            'start with dash' => [true, '-1950-10-04'],
            'invalid first char' => [false, '0836217462'],
            'empty string' => [false, ''],
            'space' => [false, 'foo bar'],
            'comma' => [false, 'foo,bar'],
            'whitespace collapse' => [true, "foobar\n"],
            'normalization' => [true, ' foobar '],
        ];
    }
}
