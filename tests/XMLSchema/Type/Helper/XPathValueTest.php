<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XMLSchema\Type\Helper\XPathValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\Helper\XPathValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(XPathValue::class)]
final class XPathValueTest extends TestCase
{
    /**
     * @param bool $shouldPass
     * @param string $xpath
     */
    #[DataProvider('provideXPath')]
    public function testString(bool $shouldPass, string $xpath): void
    {
        try {
            $value = XPathValue::fromString($xpath);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function provideXPath(): array
    {
        return [
            'xpath' => [true, '//h1/following-sibling::ul'],
            'empty string' => [false, ''],
            'nonsense' => [false, 'I know nothing'],
        ];
    }
}
