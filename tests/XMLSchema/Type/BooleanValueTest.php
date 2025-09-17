<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\DependsOnClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\BooleanTest;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\BooleanValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\BooleanValueTest
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
    #[DataProvider('provideInvalidBoolean')]
    #[DataProvider('provideValidBoolean')]
    #[DataProviderExternal(BooleanTest::class, 'provideValidBoolean')]
    #[DependsOnClass(BooleanTest::class)]
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
     * Test helpers
     */
    public function testHelpers(): void
    {
        $x = BooleanValue::fromBoolean(false);
        $this->assertFalse($x->toBoolean());

        $y = BooleanValue::fromString('1');
        $this->assertTrue($y->toBoolean());
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidBoolean(): array
    {
        return [
            'whitespace collapse' => [true, " true \n"],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidBoolean(): array
    {
        return [
            'vrai' => [false, 'vrai'],
            'faux' => [false, 'faux'],
        ];
    }
}
