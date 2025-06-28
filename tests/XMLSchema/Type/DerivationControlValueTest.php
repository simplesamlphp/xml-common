<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\DerivationControlValue;
use SimpleSAML\XMLSchema\XML\xs\DerivationControlEnum;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\DerivationControlValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(DerivationControlValue::class)]
final class DerivationControlValueTest extends TestCase
{
    /**
     * @param string $derivationControl
     * @param bool $shouldPass
     */
    #[DataProvider('provideDerivationControl')]
    public function testDerivationControlValue(string $derivationControl, bool $shouldPass): void
    {
        try {
            DerivationControlValue::fromString($derivationControl);
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
        $x = DerivationControlValue::fromEnum(DerivationControlEnum::Extension);
        $this->assertEquals(DerivationControlEnum::Extension, $x->toEnum());

        $y = DerivationControlValue::fromString('extension');
        $this->assertEquals(DerivationControlEnum::Extension, $y->toEnum());
    }


    /**
     * @return array<string, array{0: string, 1: bool}>
     */
    public static function provideDerivationControl(): array
    {
        return [
            'extension' => ['extension', true],
            'list' => ['list', true],
            'restriction' => ['restriction', true],
            'substitution' => ['substitution', true],
            'union' => ['union', true],
            'undefined' => ['undefined', false],
            'empty' => ['', false],
        ];
    }
}
