<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\BooleanValue;

/**
 * Class \SimpleSAML\Test\XML\Type\BooleanValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(BooleanValue::class)]
final class BooleanValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $boolean
     */
    #[DataProvider('provideBoolean')]
    public function testBoolean(bool $shouldPass, string $boolean): void
    {
        try {
            BooleanValue::fromString($boolean);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
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
            'whitespace collapse' => [true, ' tr ue '],
            'vrai' => [false, 'vrai'],
            'faux' => [false, 'faux'],
        ];
    }
}
