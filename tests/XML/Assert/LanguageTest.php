<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\LanguageTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class LanguageTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $language
     */
    #[DataProvider('provideInvalidLanguage')]
    #[DataProvider('provideValidLanguage')]
    public function testValidLanguage(bool $shouldPass, string $language): void
    {
        try {
            Assert::validLanguage($language);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidLanguage(): array
    {
        return [
            'one part' => [true, 'es'],
            'two parts' => [true, 'en-US'],
            'many parts' => [true, 'tlh-Kore-AQ-fonipa'],
            'three-letter primary code' => [true, 'nso'],
            'x-case' => [true, 'x-my-custom-language'],
            'i-case' => [true, 'i-klingon'],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidLanguage(): array
    {
        return [
            'empty string' => [false, ''],
            'whitespace' => [false, 'en- us'],
            'too long' => [false, 'toolongLanguageCode'],
            'not-grandfathered' => [false, 'i-sami-no'],
        ];
    }
}
