<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\AnyURITest;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\Type\BaseValue;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\{AnyURIValue, StringValue};

/**
 * Class \SimpleSAML\Test\XML\Type\BaseValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(BaseValue::class)]
final class BaseValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $base
     */
    #[DataProvider('provideValidBase')]
    #[DataProviderExternal(AnyURITest::class, 'provideValidURI')]
    #[DependsOnClass(AnyURITest::class)]
    public function testAnyURI(bool $shouldPass, string $base): void
    {
        try {
            BaseValue::fromString($base);
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
        $base = BaseValue::fromString('urn:x-simplesamlphp:namespace');
        $attr = $base->toAttribute();

        $this->assertEquals($attr->getNamespaceURI(), C::NS_XML);
        $this->assertEquals($attr->getNamespacePrefix(), 'xml');
        $this->assertEquals($attr->getAttrName(), 'base');
        $this->assertEquals($attr->getAttrValue(), 'urn:x-simplesamlphp:namespace');
    }


    /**
     */
    public function testEquals(): void
    {
        // Assert that two identical values are equal
        $this->assertTrue(AnyURIValue::fromString('hello')->equals(AnyURIValue::fromString('hello')));
        $this->assertTrue(AnyURIValue::fromString('hello')->equals(StringValue::fromString('hello')));
        $this->assertTrue(AnyURIValue::fromString('hello')->equals('hello'));

        // Assert that two different values are not equal
        $this->assertFalse(AnyURIValue::fromString('hello')->equals(AnyURIValue::fromString('world')));
        $this->assertFalse(AnyURIValue::fromString('hello')->equals(StringValue::fromString('world')));
        $this->assertFalse(AnyURIValue::fromString('hello')->equals('world'));
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidBase(): array
    {
        return [
            'trailing newline' => [true, "https://sts.windows.net/{tenantid}/\n"],
        ];
    }
}
