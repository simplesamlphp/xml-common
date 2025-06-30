<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Schema;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XMLSchema\Type\Schema\PublicValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\Schema\PublicValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(PublicValue::class)]
final class PublicValueTest extends TestCase
{
    /**
     * @param string $public
     * @param string $normalizedPublic
     */
    #[DataProvider('providePublic')]
    public function testPublic(string $public, string $normalizedPublic): void
    {
        $value = PublicValue::fromString($public);
        $this->assertEquals($normalizedPublic, $value->getValue());
        $this->assertEquals($public, $value->getRawValue());
    }


    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function providePublic(): array
    {
        return [
            'empty string' => ['', ''],
            'fpi' => [
                "-//W3C//DTD HTML 4.01 Transitional//EN",
                '-//W3C//DTD HTML 4.01 Transitional//EN',
            ],
            'trim' => [
                "  -//W3C//DTD HTML\t\n\r4.01 Transitional//EN  ",
                '-//W3C//DTD HTML 4.01 Transitional//EN',
            ],
            'replace whitespace' => [
                "-//W3C//DTD HTML\t4.01 Transitional//EN",
                '-//W3C//DTD HTML 4.01 Transitional//EN',
            ],
            'collapse' => [
                "-//W3C//DTD HTML\t\n\r4.01 Transitional//EN",
                '-//W3C//DTD HTML 4.01 Transitional//EN',
            ],
        ];
    }
}
