<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DependsOnClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\NormalizedStringTest;
use SimpleSAML\XMLSchema\Type\NormalizedStringValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\NormalizedStringValueValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(NormalizedStringValue::class)]
final class NormalizedStringValueTest extends TestCase
{
    /**
     * @param string $str
     * @param string $normalizedStringValue
     */
    #[DataProvider('provideNormalizedString')]
    #[DependsOnClass(NormalizedStringTest::class)]
    public function testNormalizedString(string $str, string $normalizedStringValue): void
    {
        $value = NormalizedStringValue::fromString($str);
        $this->assertEquals($normalizedStringValue, $value->getValue());
        $this->assertEquals($str, $value->getRawValue());
    }


    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function provideNormalizedString(): array
    {
        return [
            'preserve spaces' => ['  Snoopy  ', '  Snoopy  '],
            'replace whitespace' => ["  Snoopy\t\n\rrulez  ", '  Snoopy   rulez  '],
        ];
    }
}
