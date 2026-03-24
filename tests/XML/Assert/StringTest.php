<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;

use function chr;

/**
 * Class \SimpleSAML\Test\XML\Assert\StringTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class StringTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $str
     */
    #[DataProvider('provideString')]
    public function testString(bool $shouldPass, string $str): void
    {
        try {
            Assert::validString($str);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: boolean, 1: string}>
     */
    public static function provideString(): array
    {
        return [
            'preserve spaces' => [true, '  Snoopy  '],
            'replace whitespace' => [true, "  Snoopy\t\n\rrulez  "],
            'html' => [true, "<em>SimpleSAMLphp</em>"],
            'unicode' => [true, 'ünïcöde €Φ汉'],
            'invalid character' => [false, "Valid text with " . chr(0) . " invalid null byte"],
        ];
    }
}
