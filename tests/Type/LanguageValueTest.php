<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
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
    #[DataProvider('provideLanguage')]
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
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideLanguage(): array
    {
        return [
            'empty string' => [false, ''],
            'one part' => [true, 'es'],
            'two parts' => [true, 'en-US'],
            'many parts' => [true, 'es-this-goes-on-forever'],
            'too long' => [false, 'toolongLanguageuage'],
            'x-case' => [true, 'x-klingon'],
            'i-case' => [true, 'i-sami-no'],
            'normalization' => [true, ' en-US '],
        ];
    }
}
