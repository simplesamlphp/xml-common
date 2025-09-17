<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Schema;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Schema\WhiteSpaceValue;
use SimpleSAML\XMLSchema\XML\Enumeration\WhiteSpaceEnum;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\Schema\WhiteSpaceValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(WhiteSpaceValue::class)]
final class WhiteSpaceValueTest extends TestCase
{
    /**
     * @param string $whiteSpace
     * @param bool $shouldPass
     */
    #[DataProvider('provideWhiteSpace')]
    public function testWhiteSpaceValue(string $whiteSpace, bool $shouldPass): void
    {
        try {
            WhiteSpaceValue::fromString($whiteSpace);
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
        $x = WhiteSpaceValue::fromEnum(WhiteSpaceEnum::Collapse);
        $this->assertEquals(WhiteSpaceEnum::Collapse, $x->toEnum());

        $y = WhiteSpaceValue::fromString('collapse');
        $this->assertEquals(WhiteSpaceEnum::Collapse, $y->toEnum());
    }


    /**
     * @return array<string, array{0: string, 1: bool}>
     */
    public static function provideWhiteSpace(): array
    {
        return [
            'collapse' => ['collapse', true],
            'preserve' => ['preserve', true],
            'replace' => ['replace', true],
            'undefined' => ['undefined', false],
            'empty' => ['', false],
        ];
    }
}
