<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\IDRefTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class IDRefTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $idref
     */
    #[DataProvider('provideInvalidIDRef')]
    #[DataProvider('provideValidIDRef')]
    public function testValidIDRef(bool $shouldPass, string $idref): void
    {
        try {
            Assert::validIDRef($idref);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidIDRef(): array
    {
        return [
            'valid' => [true, 'Test'],
            'valid starts with underscore' => [true, '_Test'],
            'valid contains dashes' => [true, '_1950-10-04_10-00'],
            'valid contains dots' => [true, 'Te.st'],
            'valid contains diacriticals' => [true, 'fööbár'],
            'valid prefixed v4 UUID' => [true, '_5425e58e-e799-4884-92cc-ca64ecede32f'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidIDRef(): array
    {
        return [
            'invalid empty string' => [false, ''],
            'invalid contains wildcard' => [false, 'Te*st'],
            'invalid starts with digit' => [false, '1Test'],
            'invalid contains colon' => [false, 'Te:st'],
            'trailing newline' => [false, "Test\n"],
        ];
    }
}
