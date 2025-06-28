<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\LanguageTest;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\Type\LangValue;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;

/**
 * Class \SimpleSAML\Test\XML\Type\LangValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(LangValue::class)]
final class LangValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $language
     */
    #[DataProvider('provideInvalidLang')]
    #[DataProvider('provideValidLang')]
    #[DataProviderExternal(LanguageTest::class, 'provideValidLanguage')]
    #[DependsOnClass(LanguageTest::class)]
    public function testLanguage(bool $shouldPass, string $language): void
    {
        try {
            LangValue::fromString($language);
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
        $lang = LangValue::fromString('en-us');
        $attr = $lang->toAttribute();

        $this->assertEquals($attr->getNamespaceURI(), C::NS_XML);
        $this->assertEquals($attr->getNamespacePrefix(), 'xml');
        $this->assertEquals($attr->getAttrName(), 'lang');
        $this->assertEquals($attr->getAttrValue(), 'en-us');
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidLang(): array
    {
        return [
            'empty string' => [true, ''],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidLang(): array
    {
        return [
            'too long' => [false, 'toolongLanguage'],
        ];
    }
}
