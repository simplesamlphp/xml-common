<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\IDRefsValue;

/**
 * Class \SimpleSAML\Test\XML\Type\IDRefsValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(IDRefsValue::class)]
final class IDRefsValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $idrefs
     */
    #[DataProvider('provideIDRefs')]
    public function testIDRefs(bool $shouldPass, string $idrefs): void
    {
        try {
            IDRefsValue::fromString($idrefs);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideIDRefs(): array
    {
        return [
            'valid' => [true, 'Snoopy foobar'],
            'diacritical' => [true, 'Snööpy fööbár'],
            'start with colon' => [false, 'foobar :CMS'],
            'start with dash' => [false, '-1950-10-04 foobar'],
            'start with underscore' => [true, '_1950-10-04 foobar'],
            'invalid first char' => [false, '0836217462 1378943'],
            'empty string' => [false, ''],
            'space' => [true, 'foo bar'],
            'colon' => [false, 'foo:bar'],
            'comma' => [false, 'foo,bar'],
            'whitespace collapse' => [true, "foobar\n"],
            'normalization' => [true, ' foobar '],
        ];
    }
}
