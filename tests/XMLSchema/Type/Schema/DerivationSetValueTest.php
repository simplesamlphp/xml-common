<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Schema;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Schema\DerivationSetValue;
use SimpleSAML\XMLSchema\XML\Enumeration\DerivationControlEnum;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\DerivationSetValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(DerivationSetValue::class)]
final class DerivationSetValueTest extends TestCase
{
    /**
     * @param string $derivationSet
     * @param bool $shouldPass
     */
    #[DataProvider('provideDerivationSet')]
    public function testDerivationSetValue(string $derivationSet, bool $shouldPass): void
    {
        try {
            DerivationSetValue::fromString($derivationSet);
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
        $x = DerivationSetValue::fromEnum(DerivationControlEnum::Extension);
        $this->assertEquals(DerivationControlEnum::Extension, $x->toEnum());

        $y = DerivationSetValue::fromString('extension');
        $this->assertEquals(DerivationControlEnum::Extension, $y->toEnum());
    }


    /**
     * @return array<string, array{0: string, 1: bool}>
     */
    public static function provideDerivationSet(): array
    {
        return [
            '#all' => ['#all', true],
            'extension' => ['extension', true],
            'list' => ['list', false],
            'restriction' => ['restriction', true],
            'substitution' => ['substitution', false],
            'union' => ['union', false],
            'undefined' => ['undefined', false],
            'empty' => ['', true],
        ];
    }
}
