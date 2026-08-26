<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\IntegerTest;
use SimpleSAML\XMLSchema\Exception\RuntimeException;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\IntegerValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\IntegerOverflowTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(IntegerValue::class)]
final class IntegerOverflowTest extends TestCase
{
    /**
     * @param boolean|class-string<\Throwable> $shouldPass
     * @param string $integer
     * @param string|null $message
     */
    #[DataProvider('provideInvalidInteger')]
    #[DataProvider('provideValidInteger')]
    #[DataProviderExternal(IntegerTest::class, 'provideValidInteger')]
    public function testInteger(bool|string $shouldPass, string $integer, ?string $message = null): void
    {
        try {
            IntegerValue::fromString($integer)->toInteger();
            $this->assertTrue($shouldPass);
        } catch (RuntimeException | SchemaViolationException $e) {
            $this->assertSame($shouldPass, $e::class);
            if ($message !== null) {
                $this->assertSame($message, $e->getMessage());
            }
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidInteger(): array
    {
        return [
            'valid with whitespace collapse' => [true, " 1234 \n "],
        ];
    }


    /**
     * @return array<string, array{0: class-string<\Throwable>, 1: string, 2?: string}>
     */
    public static function provideInvalidInteger(): array
    {
        return [
            'empty' => [SchemaViolationException::class, ''],
            'invalid positive signed out-of-bounds' => [
                RuntimeException::class,
                '+9223372036854775808',
                'Cannot convert to integer: out of bounds.',
            ],
            'invalid negative signed out-of-bounds' => [
                RuntimeException::class,
                '-9223372036854775809',
                'Cannot convert to integer: out of bounds.',
            ],
            'invalid' => [SchemaViolationException::class, '0x123'],
            'invalid with fractional' => [SchemaViolationException::class, '1234.'],
            'invalid with thousands-delimiter' => [SchemaViolationException::class, '+1,234'],
        ];
    }


    public function testToIntegerWithNonWellFormedIntegerStringThrowsException(): void
    {
        $this->expectException(SchemaViolationException::class);
        $this->expectExceptionMessageMatches('/^Not a well-formed integer string\.$/');

        /* mock the internal rawValue to test the exception handling within IntegerValue::toInteger() */
        $value = $this->createPartialMock(IntegerValue::class, ['getRawValue']);
        $value->expects($this->once())->method('getRawValue')->willReturn('0x42');
        $value->toInteger();
    }
}
