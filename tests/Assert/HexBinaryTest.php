<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\Assert\HexBinaryTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class HexBinaryTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $name
     */
    #[DataProvider('provideInvalidHexBinary')]
    #[DataProvider('provideValidHexBinary')]
    public function testHexBinary(bool $shouldPass, string $name): void
    {
        try {
            Assert::validHexBinary($name);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidHexBinary(): array
    {
        return [
            'valid' => [true, '3f3c6d78206c657673726f693d6e3122302e20226e656f636964676e223d54552d4622383e3f'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidHexBinary(): array
    {
        return [
            'empty' => [false, ''],
            'base64' => [false, 'U2ltcGxlU0FNTHBocA=='],
            'invalid' => [false, '3f3r'],
            'bogus' => [false, '&*$(#&^@!(^%$'],
            'length not dividable by 4' => [false, '3f3'],
        ];
    }
}
