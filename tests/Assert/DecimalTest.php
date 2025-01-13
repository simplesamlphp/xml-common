<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\DecimalTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class DecimalTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $decimal
     */
    #[DataProvider('provideDecimal')]
    public function testValidDecimal(bool $shouldPass, string $decimal): void
    {
        try {
            Assert::validDecimal($decimal);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideDecimal(): array
    {
        return [
            'empty' => [false, ''],
            'valid decimal' => [true, '123.456'],
            'valid positive signed' => [true, '+123.456'],
            'valid negative signed' => [true, '-123.456'],
            'valid fractional only' => [true, '-.456'],
            'valid without fraction' => [true, '-456'],
            'invalid with space' => [false, '1 234.456'],
            'invalid scientific notation' => [false, '1234.456E+2'],
            'invalid signed with space' => [false, '+ 1234.456'],
            'invalid with thousands-delimiter' => [false, '+1,234.456'],
        ];
    }
}