<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\IDRefsTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class IDRefsTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $idrefs
     */
    #[DataProvider('provideInvalidIDRefs')]
    #[DataProvider('provideValidIDRefs')]
    public function testValidIDRefs(bool $shouldPass, string $idrefs): void
    {
        try {
            Assert::validIDRefs($idrefs);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidIDRefs(): array
    {
        return [
            'valid' => [true, 'Snoopy foobar'],
            'diacritical' => [true, 'Snööpy fööbár'],
            'start with underscore' => [true, '_1950-10-04 foobar'],
            'space' => [true, 'foo bar'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidIDRefs(): array
    {
        return [
            'start with colon' => [false, 'foobar :CMS'],
            'start with dash' => [false, '-1950-10-04 foobar'],
            'invalid first char' => [false, '0836217462 1378943'],
            'empty string' => [false, ''],
            'colon' => [false, 'foo:bar'],
            'comma' => [false, 'foo,bar'],
        ];
    }
}
