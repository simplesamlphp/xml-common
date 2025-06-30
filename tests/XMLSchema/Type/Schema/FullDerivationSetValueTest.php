<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Schema;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Schema\FullDerivationSetValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\Schema\FullDerivationSetValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(FullDerivationSetValue::class)]
final class FullDerivationSetValueTest extends TestCase
{
    /**
     * @param string $fullDerivationSet
     * @param bool $shouldPass
     */
    #[DataProvider('provideFullDerivationSet')]
    public function testFullDerivationSetValue(string $fullDerivationSet, bool $shouldPass): void
    {
        try {
            FullDerivationSetValue::fromString($fullDerivationSet);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: string, 1: bool}>
     */
    public static function provideFullDerivationSet(): array
    {
        return [
            '#all' => ['#all', true],
            '#all combined' => ['#all list restriction union', false],
            'extension' => ['extension', true],
            'list' => ['list', true],
            'union' => ['union', true],
            'restriction' => ['restriction', true],
            'substitution' => ['substitution', false],
            'combined' => ['restriction union list', true],
            'multiple spaces and newlines' => [
                "restriction  list \n union",
                true,
            ],
            'undefined' => ['undefined', false],
            'empty' => ['', true],
        ];
    }
}
