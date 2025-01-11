<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\LangValue;

/**
 * Class \SimpleSAML\Test\Type\LangValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(LangValue::class)]
final class LangValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $lang
     */
    #[DataProvider('provideLang')]
    public function testLang(bool $shouldPass, string $lang): void
    {
        try {
            LangValue::fromString($lang);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
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
            'normalization' => [true, ' en-US '],
        ];
    }
}
