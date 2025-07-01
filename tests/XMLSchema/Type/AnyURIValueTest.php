<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\AnyURITest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\{AnyURIValue, StringValue};

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\AnyURIValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(AnyURIValue::class)]
final class AnyURIValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $uri
     */
    #[DataProvider('provideValidURI')]
    #[DataProviderExternal(AnyURITest::class, 'provideValidURI')]
    #[DependsOnClass(AnyURITest::class)]
    public function testAnyURI(bool $shouldPass, string $uri): void
    {
        try {
            AnyURIValue::fromString($uri);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     */
    public function testEquals(): void
    {
        // Assert that two identical values are equal
        $this->assertTrue(AnyURIValue::fromString('hello')->equals(AnyURIValue::fromString('hello')));
        $this->assertTrue(AnyURIValue::fromString('hello')->equals(StringValue::fromString('hello')));
        $this->assertTrue(AnyURIValue::fromString('hello')->equals('hello'));

        // Assert that two different values are not equal
        $this->assertFalse(AnyURIValue::fromString('hello')->equals(AnyURIValue::fromString('world')));
        $this->assertFalse(AnyURIValue::fromString('hello')->equals(StringValue::fromString('world')));
        $this->assertFalse(AnyURIValue::fromString('hello')->equals('world'));
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidURI(): array
    {
        return [
            'preceding newline' => [true, "\nhttps://sts.windows.net/{tenantid}/"],
            'trailing newline' => [true, "https://sts.windows.net/{tenantid}/\n"],
            'both side whitespace' => [true, "\t https://sts.windows.net/{tenantid}/\n "],
        ];
    }
}
