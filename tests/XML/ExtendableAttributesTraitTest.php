<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\Test\Helper\ExtendableAttributesElement;
use SimpleSAML\XML\Attribute;
use SimpleSAML\XML\ExtendableAttributesTrait;
use SimpleSAML\XML\TestUtils\{SchemaValidationTestTrait, SerializableElementTestTrait};
use SimpleSAML\XMLSchema\Type\StringValue;
use SimpleSAML\XMLSchema\XML\Enumeration\NamespaceEnum;

/**
 * Class \SimpleSAML\XML\ExtendableAttributesTraitTest
 *
 * @package simplesamlphp\xml-common
 */
#[CoversClass(SchemaValidationTestTrait::class)]
#[CoversClass(SerializableElementTestTrait::class)]
#[CoversClass(ExtendableAttributesTrait::class)]
final class ExtendableAttributesTraitTest extends TestCase
{
    /** @var \SimpleSAML\XML\Attribute */
    protected static Attribute $local;

    /** @var \SimpleSAML\XML\Attribute */
    protected static Attribute $other;

    /** @var \SimpleSAML\XML\Attribute */
    protected static Attribute $target;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$local = new Attribute(null, '', 'some', StringValue::fromString('localValue'));

        self::$target = new Attribute(
            'urn:x-simplesamlphp:namespace',
            'ssp',
            'some',
            StringValue::fromString('targetValue'),
        );

        self::$other = new Attribute('urn:custom:dummy', 'dummy', 'some', StringValue::fromString('dummyValue'));
    }


    /**
     */
    public function testHasAttributeNS(): void
    {
        $c = new ExtendableAttributesElement([self::$other]);

        $this->assertTrue($c->hasAttributeNS('urn:custom:dummy', 'some'));
        $this->assertFalse($c->hasAttributeNS('urn:x-simplesamlphp:namespace', 'some'));
    }


    /**
     */
    public function testGetAttributeNS(): void
    {
        $c = new ExtendableAttributesElement([self::$other]);

        $this->assertInstanceOf(Attribute::class, $c->getAttributeNS('urn:custom:dummy', 'some'));
        $this->assertNull($c->getAttributeNS('urn:x-simplesamlphp:namespace', 'some'));
    }


    /**
     */
    public function testIllegalNamespaceComboThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$target]) extends ExtendableAttributesElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getAttributeNamespace(): array|NamespaceEnum
            {
                return [NamespaceEnum::Other, NamespaceEnum::Any];
            }
        };
    }


    public function testEmptyNamespaceArrayThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([]) extends ExtendableAttributesElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getAttributeNamespace(): array|NamespaceEnum
            {
                return [];
            }
        };
    }


    /**
     */
    public function testOtherNamespacePassingOtherSucceeds(): void
    {
        $c = new class ([self::$other]) extends ExtendableAttributesElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getAttributeNamespace(): array|NamespaceEnum
            {
                return NamespaceEnum::Other;
            }
        };

        $this->assertEquals(NamespaceEnum::Other, $c->getAttributeNamespace());
    }


    /**
     */
    public function testOtherNamespacePassingLocalThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$local]) extends ExtendableAttributesElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getAttributeNamespace(): array|NamespaceEnum
            {
                return NamespaceEnum::Other;
            }
        };
    }


    /**
     */
    public function testTargetNamespacePassingTargetSucceeds(): void
    {
        $c = new class ([self::$target]) extends ExtendableAttributesElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getAttributeNamespace(): array|NamespaceEnum
            {
                return NamespaceEnum::TargetNamespace;
            }
        };

        $this->assertEquals(NamespaceEnum::TargetNamespace, $c->getAttributeNamespace());
    }


    /**
     */
    public function testTargetNamespacePassingTargetArraySucceeds(): void
    {
        $c = new class ([self::$target]) extends ExtendableAttributesElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getAttributeNamespace(): array|NamespaceEnum
            {
                return [NamespaceEnum::TargetNamespace];
            }
        };

        $this->assertEquals([NamespaceEnum::TargetNamespace], $c->getAttributeNamespace());
    }


    /**
     */
    public function testTargetNamespacePassingTargetArraySucceedsWithLocal(): void
    {
        $c = new class ([self::$target]) extends ExtendableAttributesElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getAttributeNamespace(): array|NamespaceEnum
            {
                return [NamespaceEnum::TargetNamespace, NamespaceEnum::Local];
            }
        };

        $this->assertEquals([NamespaceEnum::TargetNamespace, NamespaceEnum::Local], $c->getAttributeNamespace());
    }


    /**
     */
    public function testTargetNamespacePassingOtherThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$other]) extends ExtendableAttributesElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getAttributeNamespace(): array|NamespaceEnum
            {
                return NamespaceEnum::TargetNamespace;
            }
        };
    }


    /**
     */
    public function testLocalNamespacePassingLocalSucceeds(): void
    {
        $c = new class ([self::$local]) extends ExtendableAttributesElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getAttributeNamespace(): array|NamespaceEnum
            {
                return NamespaceEnum::Local;
            }
        };

        $this->assertEquals(NamespaceEnum::Local, $c->getAttributeNamespace());
    }


    /**
     */
    public function testLocalNamespacePassingLocalArraySucceeds(): void
    {
        $c = new class ([self::$local]) extends ExtendableAttributesElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getAttributeNamespace(): array|NamespaceEnum
            {
                return [NamespaceEnum::Local];
            }
        };

        $this->assertEquals([NamespaceEnum::Local], $c->getAttributeNamespace());
    }


    /**
     */
    public function testLocalNamespacePassingOtherThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$other]) extends ExtendableAttributesElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getAttributeNamespace(): array|NamespaceEnum
            {
                return NamespaceEnum::Local;
            }
        };
    }


    /**
     */
    public function testAnyNamespacePassingTargetSucceeds(): void
    {
        $c = new class ([self::$target]) extends ExtendableAttributesElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getAttributeNamespace(): array|NamespaceEnum
            {
                return NamespaceEnum::Any;
            }
        };

        $this->assertEquals(NamespaceEnum::Any, $c->getAttributeNamespace());
    }


    /**
     */
    public function testAnyNamespacePassingOtherSucceeds(): void
    {
        $c = new class ([self::$other]) extends ExtendableAttributesElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getAttributeNamespace(): array|NamespaceEnum
            {
                return NamespaceEnum::Any;
            }
        };

        $this->assertEquals(NamespaceEnum::Any, $c->getAttributeNamespace());
    }
}
