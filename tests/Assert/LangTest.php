<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert;

/**
 * Class \SimpleSAML\Test\XML\Assert\LangTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(Assert::class)]
final class LangTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $lang
     */
    #[DataProvider('provideLang')]
    public function testValidLang(bool $shouldPass, string $lang): void
    {
        try {
            Assert::validLang($lang);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideLang(): array
    {
        return [
            'empty string' => [false, ''],
            'one part' => [true, 'es'],
            'two parts' => [true, 'en-US'],
            'many parts' => [true, 'es-this-goes-on-forever'],
            'too long' => [false, 'toolonglanguage'],
            'x-case' => [true, 'x-klingon'],
            'i-case' => [true, 'i-sami-no'],
        ];
    }
}
