<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XMLSchema\Type\IntegerValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\IntegerFromIntegerTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(IntegerValue::class)]
final class IntegerFromIntegerTest extends TestCase
{
    /**
     * @param int $integer
     */
    #[DataProvider('provideIntegers')]
    public function testFromInteger(int $integer): void
    {
        $value = IntegerValue::fromInteger($integer);

        $this->assertSame((string) $integer, $value->getValue());
        $this->assertSame($integer, $value->toInteger());
    }


    /**
     * @return array<string, array{0: int}>
     */
    public static function provideIntegers(): array
    {
        return [
            'negative integer' => [-1234],
            'zero' => [0],
            'positive integer' => [1234],
            'minimum integer' => [PHP_INT_MIN],
            'maximum integer' => [PHP_INT_MAX],
        ];
    }
}
