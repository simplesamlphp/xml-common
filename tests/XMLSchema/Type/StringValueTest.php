<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DependsOnClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\StringTest;
use SimpleSAML\XMLSchema\Type\StringValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\StringValueValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(StringValue::class)]
final class StringValueTest extends TestCase
{
    /**
     * @param string $str
     * @param string $stringValue
     */
    #[DataProvider('provideString')]
    #[DependsOnClass(StringTest::class)]
    public function testString(string $str, string $stringValue): void
    {
        $value = StringValue::fromString($str);
        $this->assertEquals($stringValue, $value->getValue());
        $this->assertEquals($str, $value->getRawValue());
    }


    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function provideString(): array
    {
        return [
            'empty string' => ['', ''],
            'no trim' => ['Snoopy  ', 'Snoopy  '],
            'no replace whitespace' => ["Snoopy\trulez", "Snoopy\trulez"],
            'no collapse' => ["Snoopy\t\n\rrulez", "Snoopy\t\n\rrulez"],
        ];
    }
}
