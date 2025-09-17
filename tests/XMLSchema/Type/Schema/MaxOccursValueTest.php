<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Schema;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Schema\MaxOccursValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\Schema\MaxOccursValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(MaxOccursValue::class)]
final class MaxOccursValueTest extends TestCase
{
    /**
     * @param string $maxOccurs
     * @param bool $shouldPass
     */
    #[DataProvider('provideMaxOccurs')]
    public function testMaxOccursValue(string $maxOccurs, bool $shouldPass): void
    {
        try {
            MaxOccursValue::fromString($maxOccurs);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: string, 1: bool}>
     */
    public static function provideMaxOccurs(): array
    {
        return [
            'negative' => ['-1', false],
            'zero' => ['0', true],
            'positive' => ['1', true],
            'unbounded' => ['unbounded', true],
            'empty' => ['', false],
        ];
    }
}
