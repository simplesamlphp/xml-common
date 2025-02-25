<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\TokenTest;
use SimpleSAML\XML\Type\TokenValue;

/**
 * Class \SimpleSAML\Test\XML\Type\TokenValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(TokenValue::class)]
final class TokenValueTest extends TestCase
{
    /**
     * @param string $token
     * @param string $normalizedToken
     */
    #[DataProvider('provideToken')]
    #[DependsOnClass(TokenTest::class)]
    public function testToken(string $token, string $normalizedToken): void
    {
        $value = TokenValue::fromString($token);
        $this->assertEquals($normalizedToken, $value->getValue());
        $this->assertEquals($token, $value->getRawValue());
    }


    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function provideToken(): array
    {
        return [
            'empty string' => ['', ''],
            'trim' => ['  Snoopy  ', 'Snoopy'],
            'replace whitespace' => ["Snoopy\trulez", 'Snoopy rulez'],
            'collapse' => ["Snoopy\t\n\rrulez", 'Snoopy rulez'],
        ];
    }
}
