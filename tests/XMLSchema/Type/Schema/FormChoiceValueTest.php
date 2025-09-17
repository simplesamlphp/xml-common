<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Schema;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Schema\FormChoiceValue;
use SimpleSAML\XMLSchema\XML\Enumeration\FormChoiceEnum;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\Schema\FormChoiceValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(FormChoiceValue::class)]
final class FormChoiceValueTest extends TestCase
{
    /**
     * @param string $formChoice
     * @param bool $shouldPass
     */
    #[DataProvider('provideFormChoice')]
    public function testFormChoiceValue(string $formChoice, bool $shouldPass): void
    {
        try {
            FormChoiceValue::fromString($formChoice);
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
        $x = FormChoiceValue::fromEnum(FormChoiceEnum::Qualified);
        $this->assertEquals(FormChoiceEnum::Qualified, $x->toEnum());

        $y = FormChoiceValue::fromString('qualified');
        $this->assertEquals(FormChoiceEnum::Qualified, $y->toEnum());
    }


    /**
     * @return array<string, array{0: string, 1: bool}>
     */
    public static function provideFormChoice(): array
    {
        return [
            'qualified' => ['qualified', true],
            'unqualified' => ['unqualified', true],
            'undefined' => ['undefined', false],
            'empty' => ['', false],
        ];
    }
}
