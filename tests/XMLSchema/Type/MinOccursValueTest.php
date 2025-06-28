<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\MinOccursValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\MinOccursValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(MinOccursValue::class)]
final class MinOccursValueTest extends TestCase
{
    /**
     * @param string $minOccurs
     * @param bool $shouldPass
     */
    #[DataProvider('provideMinOccurs')]
    public function testMinOccursValue(string $minOccurs, bool $shouldPass): void
    {
        try {
            MinOccursValue::fromString($minOccurs);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: string, 1: bool}>
     */
    public static function provideMinOccurs(): array
    {
        return [
            'negative' => ['-1', false],
            'zero' => ['0', true],
            'positive' => ['1', true],
            'unbounded' => ['unbounded', false],
            'empty' => ['', false],
        ];
    }
}
