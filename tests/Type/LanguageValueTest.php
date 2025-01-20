<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\LanguageTest;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\LanguageValue;

/**
 * Class \SimpleSAML\Test\XML\Type\LanguageValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(LanguageValue::class)]
final class LanguageValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $language
     */
    #[DataProvider('provideInvalidLanguage')]
    #[DataProvider('provideValidLanguage')]
    #[DataProviderExternal(LanguageTest::class, 'provideValidLanguage')]
    #[DependsOnClass(LanguageTest::class)]
    public function testLanguage(bool $shouldPass, string $language): void
    {
        try {
            LanguageValue::fromString($language);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidLanguage(): array
    {
        return [
            'normalization' => [true, ' en-US '],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidLanguage(): array
    {
        return [
            'empty string' => [false, ''],
            'too long' => [false, 'toolongLanguage'],
        ];
    }
}
