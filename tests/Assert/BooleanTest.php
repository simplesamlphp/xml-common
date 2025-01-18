<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\BooleanTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class BooleanTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $boolean
     */
    #[DataProvider('provideBoolean')]
    public function testValidBoolean(bool $shouldPass, string $boolean): void
    {
        try {
            Assert::validBoolean($boolean);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideBoolean(): array
    {
        return [
            'true' => [true, 'true'],
            'false' => [true, 'false'],
            'one' => [true, '1'],
            'zero' => [true, '0'],
            'vrai' => [false, 'vrai'],
            'faux' => [false, 'faux'],
        ];
    }
}
