<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\DependsOnClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\IDRefsTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\IDRefsValue;

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
    #[DataProvider('provideInvalidIDRefs')]
    #[DataProvider('provideValidIDRefs')]
    #[DataProviderExternal(IDRefsTest::class, 'provideValidIDRefs')]
    #[DependsOnClass(IDRefsTest::class)]
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
     * Test the toArray function
     */
    #[DependsOnClass(IDRefsTest::class)]
    public function testToArray(): void
    {
        $idrefs = IDRefsValue::fromString("foo \nbar  baz");
        $this->assertEquals(['foo', 'bar', 'baz'], $idrefs->toArray());
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidIDRefs(): array
    {
        return [
            'whitespace collapse' => [true, "foobar\n"],
            'normalization' => [true, ' foobar '],
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
