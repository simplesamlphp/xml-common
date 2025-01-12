<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\NormalizedStringTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class NormalizedStringTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $str
     */
    #[DataProvider('provideNormalizedString')]
    public function testNormalizedString(bool $shouldPass, string $str): void
    {
        try {
            Assert::validNormalizedString($str);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: boolean, 1: string}>
     */
    public static function provideNormalizedString(): array
    {
        return [
            'preserve spaces' => [true, '  Snoopy  '],
            'replace whitespace' => [true, "  Snoopy\t\n\rrulez  "],
        ];
    }
}
