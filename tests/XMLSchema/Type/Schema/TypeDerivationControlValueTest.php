<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Schema;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Schema\TypeDerivationControlValue;
use SimpleSAML\XMLSchema\XML\Enumeration\DerivationControlEnum;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\Schema\TypeDerivationControlValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(TypeDerivationControlValue::class)]
final class TypeDerivationControlValueTest extends TestCase
{
    /**
     * @param string $typeDerivationControl
     * @param bool $shouldPass
     */
    #[DataProvider('provideTypeDerivationControl')]
    public function testTypeDerivationControlValue(string $typeDerivationControl, bool $shouldPass): void
    {
        try {
            TypeDerivationControlValue::fromString($typeDerivationControl);
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
        $x = TypeDerivationControlValue::fromEnum(DerivationControlEnum::Extension);
        $this->assertEquals(DerivationControlEnum::Extension, $x->toEnum());

        $y = TypeDerivationControlValue::fromString('extension');
        $this->assertEquals(DerivationControlEnum::Extension, $y->toEnum());
    }


    /**
     * @return array<string, array{0: string, 1: bool}>
     */
    public static function provideTypeDerivationControl(): array
    {
        return [
            'extension' => ['extension', true],
            'list' => ['list', true],
            'restriction' => ['restriction', true],
            'substitution' => ['substitution', false],
            'union' => ['union', true],
            'undefined' => ['undefined', false],
            'empty' => ['', false],
        ];
    }
}
