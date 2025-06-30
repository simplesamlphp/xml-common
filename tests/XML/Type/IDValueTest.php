<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\IDTest;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\Type\IDValue;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\{IDValue as BaseIDValue, StringValue};

/**
 * Class \SimpleSAML\Test\XML\Type\IDValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(IDValue::class)]
final class IDValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $id
     */
    #[DataProviderExternal(IDTest::class, 'provideValidID')]
    #[DependsOnClass(IDTest::class)]
    public function testID(bool $shouldPass, string $id): void
    {
        try {
            IDValue::fromString($id);
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
        $id = IDValue::fromString('phpunit');
        $attr = $id->toAttribute();

        $this->assertEquals($attr->getNamespaceURI(), C::NS_XML);
        $this->assertEquals($attr->getNamespacePrefix(), 'xml');
        $this->assertEquals($attr->getAttrName(), 'id');
        $this->assertEquals($attr->getAttrValue(), 'phpunit');
    }
}
