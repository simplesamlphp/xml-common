<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Type\TokenValue;

/**
 * Class \SimpleSAML\Test\Type\TokenValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(TokenValue::class)]
final class TokenValueTest extends TestCase
{
    /**
     * @param string $str
     * @param string $normalizedString
     */
    #[DataProvider('provideString')]
    public function testToken(string $str, string $normalizedString): void
    {
        $value = TokenValue::fromString($str);
        $this->assertEquals($normalizedString, $value->getValue());
        $this->assertEquals($str, $value->getRawValue());
    }


    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function provideString(): array
    {
        return [
            'empty string' => ['', ''],
            'trim' => ['  Snoopy  ', 'Snoopy'],
            'replace whitespace' => ["Snoopy\trulez", 'Snoopy rulez'],
            'collapse' => ["Snoopy\t\n\rrulez", 'Snoopy rulez'],
        ];
    }
}
