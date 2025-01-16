<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
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
    #[DataProvider('provideIDRef')]
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
     * @return array<int, array{0: bool, 1: string}>
     */
    public static function provideIDRef(): array
    {
        return [
            [true, 'Test'],
            // May start with an underscore
            [true, '_Test'],
            // May contain dashes
            [true, '_1950-10-04_10-00'],
            // May contain dots
            [true, 'Te.st'],
            // May contain diacriticals
            [true, 'fööbár'],
            // Prefixed v4 UUID
            [true, '_5425e58e-e799-4884-92cc-ca64ecede32f'],
            // An empty value is not valid, unless xsi:nil is used
            [false, ''],
            // Wildcards are not allowed
            [false, 'Te*st'],
            // May not start with a digit
            [false, '1Test'],
            // May not contain a colon
            [false, 'Te:st'],
            // Trailing newlines are forbidden
            [false, "Test\n"],
        ];
    }
}
