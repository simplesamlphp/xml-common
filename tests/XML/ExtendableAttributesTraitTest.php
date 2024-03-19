<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\Test\XML\ExtendableAttributesElement;
use SimpleSAML\XML\Attribute;
use SimpleSAML\XML\ExtendableAttributesTrait;
use SimpleSAML\XML\TestUtils\SchemaValidationTestTrait;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XML\XsNamespace as NS;

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
        self::$local = new Attribute(null, '', 'some', 'localValue');

        self::$target = new Attribute('urn:x-simplesamlphp:namespace', 'ssp', 'some', 'targetValue');

        self::$other = new Attribute('urn:custom:dummy', 'dummy', 'some', 'dummyValue');
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
            /** @return array<int, NS>|NS */
            public function getAttributeNamespace(): array|NS
            {
                return [NS::OTHER, NS::ANY];
            }
        };
    }


    public function testEmptyNamespaceArrayThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([]) extends ExtendableAttributesElement {
            /** @return array<int, NS>|NS */
            public function getAttributeNamespace(): array|NS
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
            /** @return array<int, NS>|NS */
            public function getAttributeNamespace(): array|NS
            {
                return NS::OTHER;
            }
        };

        $this->assertEquals(NS::OTHER, $c->getAttributeNamespace());
    }


    /**
     */
    public function testOtherNamespacePassingLocalThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$local]) extends ExtendableAttributesElement {
            /** @return array<int, NS>|NS */
            public function getAttributeNamespace(): array|NS
            {
                return NS::OTHER;
            }
        };
    }


    /**
     */
    public function testTargetNamespacePassingTargetSucceeds(): void
    {
        $c = new class ([self::$target]) extends ExtendableAttributesElement {
            /** @return array<int, NS>|NS */
            public function getAttributeNamespace(): array|NS
            {
                return NS::TARGET;
            }
        };

        $this->assertEquals(NS::TARGET, $c->getAttributeNamespace());
    }


    /**
     */
    public function testTargetNamespacePassingTargetArraySucceeds(): void
    {
        $c = new class ([self::$target]) extends ExtendableAttributesElement {
            /** @return array<int, NS>|NS */
            public function getAttributeNamespace(): array|NS
            {
                return [NS::TARGET];
            }
        };

        $this->assertEquals([NS::TARGET], $c->getAttributeNamespace());
    }


    /**
     */
    public function testTargetNamespacePassingTargetArraySucceedsWithLocal(): void
    {
        $c = new class ([self::$target]) extends ExtendableAttributesElement {
            /** @return array<int, NS>|NS */
            public function getAttributeNamespace(): array|NS
            {
                return [NS::TARGET, NS::LOCAL];
            }
        };

        $this->assertEquals([NS::TARGET, NS::LOCAL], $c->getAttributeNamespace());
    }


    /**
     */
    public function testTargetNamespacePassingOtherThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$other]) extends ExtendableAttributesElement {
            /** @return array<int, NS>|NS */
            public function getAttributeNamespace(): array|NS
            {
                return NS::TARGET;
            }
        };
    }


    /**
     */
    public function testLocalNamespacePassingLocalSucceeds(): void
    {
        $c = new class ([self::$local]) extends ExtendableAttributesElement {
            /** @return array<int, NS>|NS */
            public function getAttributeNamespace(): array|NS
            {
                return NS::LOCAL;
            }
        };

        $this->assertEquals(NS::LOCAL, $c->getAttributeNamespace());
    }


    /**
     */
    public function testLocalNamespacePassingLocalArraySucceeds(): void
    {
        $c = new class ([self::$local]) extends ExtendableAttributesElement {
            /** @return array<int, NS>|NS */
            public function getAttributeNamespace(): array|NS
            {
                return [NS::LOCAL];
            }
        };

        $this->assertEquals([NS::LOCAL], $c->getAttributeNamespace());
    }


    /**
     */
    public function testLocalNamespacePassingTargetThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$target]) extends ExtendableAttributesElement {
            /** @return array<int, NS>|NS */
            public function getAtributeNamespace(): array|NS
            {
                return NS::LOCAL;
            }
        };
    }



    /**
     */
    public function testLocalNamespacePassingOtherThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$other]) extends ExtendableAttributesElement {
            /** @return array<int, NS>|NS */
            public function getAttributeNamespace(): array|NS
            {
                return NS::LOCAL;
            }
        };
    }


    /**
     */
    public function testAnyNamespacePassingTargetSucceeds(): void
    {
        $c = new class ([self::$target]) extends ExtendableAttributesElement {
            /** @return array<int, NS>|NS */
            public function getAttributeNamespace(): array|NS
            {
                return NS::ANY;
            }
        };

        $this->assertEquals(NS::ANY, $c->getAttributeNamespace());
    }


    /**
     */
    public function testAnyNamespacePassingOtherSucceeds(): void
    {
        $c = new class ([self::$other]) extends ExtendableAttributesElement {
            /** @return array<int, NS>|NS */
            public function getAttributeNamespace(): array|NS
            {
                return NS::ANY;
            }
        };

        $this->assertEquals(NS::ANY, $c->getAttributeNamespace());
    }
}
