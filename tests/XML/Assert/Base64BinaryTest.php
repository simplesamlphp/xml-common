<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\Base64BinaryTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class Base64BinaryTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $base64
     */
    #[DataProvider('provideInvalidBase64')]
    #[DataProvider('provideValidBase64')]
    public function testValidBase64Binary(bool $shouldPass, string $base64): void
    {
        try {
            Assert::validBase64Binary($base64);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidBase64(): array
    {
        return [
            'valid' => [true, 'U2ltcGxlU0FNTHBocA=='],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidBase64(): array
    {
        return [
            'empty' => [false, ''],
            'illegal characters' => [false, '&*$(#&^@!(^%$'],
            'length not dividable by 4' => [false, 'U2ltcGxlU0FTHBocA=='],
            'whitespace' => [false, 'U2ltcGxl U0FNTHBocA=='],
        ];
    }
}
