<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\Type\SpaceValue;
use SimpleSAML\XML\XML\xml\SpaceEnum;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;

/**
 * Class \SimpleSAML\Test\XML\Type\SpaceValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(SpaceValue::class)]
final class SpaceValueTest extends TestCase
{
    /**
     * @param string $space
     * @param bool $shouldPass
     */
    #[DataProvider('provideSpace')]
    public function testSpaceValue(string $space, bool $shouldPass): void
    {
        try {
            SpaceValue::fromString($space);
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
        $lang = SpaceValue::fromString('default');
        $attr = $lang->toAttribute();

        $this->assertEquals($attr->getNamespaceURI(), C::NS_XML);
        $this->assertEquals($attr->getNamespacePrefix(), 'xml');
        $this->assertEquals($attr->getAttrName(), 'space');
        $this->assertEquals($attr->getAttrValue(), 'default');

        //
        $x = SpaceValue::fromEnum(SpaceEnum::Default);
        $this->assertEquals(SpaceEnum::Default, $x->toEnum());

        $y = SpaceValue::fromString('default');
        $this->assertEquals(SpaceEnum::Default, $y->toEnum());
    }


    /**
     * @return array<string, array{0: string, 1: bool}>
     */
    public static function provideSpace(): array
    {
        return [
            'default' => ['default', true],
            'preserve' => ['preserve', true],
            'undefined' => ['undefined', false],
            'empty' => ['', false],
        ];
    }
}
