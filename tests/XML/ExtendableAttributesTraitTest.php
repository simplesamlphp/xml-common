<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\Test\Helper\ExtendableAttributesElement;
use SimpleSAML\XML\Attribute;
use SimpleSAML\XMLSchema\Type\StringValue;
use SimpleSAML\XMLSchema\XML\Constants\NS;

/**
 * Class \SimpleSAML\XML\ExtendableAttributesTraitTest
 *
 * @package simplesamlphp\xml-common
 */
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
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getAttributeNamespace(): array|string
            {
                return [NS::OTHER, NS::ANY];
            }
        };
    }


    public function testEmptyNamespaceArrayThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([]) extends ExtendableAttributesElement {
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getAttributeNamespace(): array|string
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
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getAttributeNamespace(): array|string
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
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getAttributeNamespace(): array|string
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
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getAttributeNamespace(): array|string
            {
                return NS::TARGETNAMESPACE;
            }
        };

        $this->assertEquals(NS::TARGETNAMESPACE, $c->getAttributeNamespace());
    }


    /**
     */
    public function testTargetNamespacePassingTargetArraySucceeds(): void
    {
        $c = new class ([self::$target]) extends ExtendableAttributesElement {
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getAttributeNamespace(): array|string
            {
                return [NS::TARGETNAMESPACE];
            }
        };

        $this->assertEquals([NS::TARGETNAMESPACE], $c->getAttributeNamespace());
    }


    /**
     */
    public function testTargetNamespacePassingTargetArraySucceedsWithLocal(): void
    {
        $c = new class ([self::$target]) extends ExtendableAttributesElement {
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getAttributeNamespace(): array|string
            {
                return [NS::TARGETNAMESPACE, NS::LOCAL];
            }
        };

        $this->assertEquals([NS::TARGETNAMESPACE, NS::LOCAL], $c->getAttributeNamespace());
    }


    /**
     */
    public function testTargetNamespacePassingOtherThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$other]) extends ExtendableAttributesElement {
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getAttributeNamespace(): array|string
            {
                return NS::TARGETNAMESPACE;
            }
        };
    }


    /**
     */
    public function testLocalNamespacePassingLocalSucceeds(): void
    {
        $c = new class ([self::$local]) extends ExtendableAttributesElement {
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getAttributeNamespace(): array|string
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
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getAttributeNamespace(): array|string
            {
                return [NS::LOCAL];
            }
        };

        $this->assertEquals([NS::LOCAL], $c->getAttributeNamespace());
    }


    /**
     */
    public function testLocalNamespacePassingOtherThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$other]) extends ExtendableAttributesElement {
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getAttributeNamespace(): array|string
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
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getAttributeNamespace(): array|string
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
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getAttributeNamespace(): array|string
            {
                return NS::ANY;
            }
        };

        $this->assertEquals(NS::ANY, $c->getAttributeNamespace());
    }
}
