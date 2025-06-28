<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\UseValue;
use SimpleSAML\XMLSchema\XML\xs\UseEnum;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\UseValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(UseValue::class)]
final class UseValueTest extends TestCase
{
    /**
     * @param string $use
     * @param bool $shouldPass
     */
    #[DataProvider('provideUse')]
    public function testUseValue(string $use, bool $shouldPass): void
    {
        try {
            UseValue::fromString($use);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * Test helpers
     */
    public function testHelpers(): void
    {
        $x = UseValue::fromEnum(UseEnum::Optional);
        $this->assertEquals(UseEnum::Optional, $x->toEnum());

        $y = UseValue::fromString('optional');
        $this->assertEquals(UseEnum::Optional, $y->toEnum());
    }


    /**
     * @return array<string, array{0: string, 1: bool}>
     */
    public static function provideUse(): array
    {
        return [
            'optional' => ['optional', true],
            'prohibited' => ['prohibited', true],
            'required' => ['required', true],
            'undefined' => ['undefined', false],
            'empty' => ['', false],
        ];
    }
}
