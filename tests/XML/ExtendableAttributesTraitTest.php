<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\Assert;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\Test\XML\ExtendableAttributesElement;
use SimpleSAML\Test\XML\ExtendableAttributesTestTrait;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\Attribute;

use function dirname;

/**
 * Class \SimpleSAML\XML\ExtendableAttributesTraitTest
 *
 * @covers \SimpleSAML\XML\TestUtils\SchemaValidationTestTrait
 * @covers \SimpleSAML\XML\TestUtils\SerializableElementTestTrait
 * @covers \SimpleSAML\XML\ExtendableAttributesTrait
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
    public function testInvalidNamespaceThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$target]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return 'wrong';
            }
        };
    }


    /**
     */
    public function testIllegalNamespaceComboThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$target]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return [C::XS_ANY_NS_OTHER, C::XS_ANY_NS_ANY];
            }
        };
    }


    public function testEmptyNamespaceArrayThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([]) extends ExtendableAttributesElement {
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
            public function getAttributeNamespace(): array|string
            {
                return C::XS_ANY_NS_OTHER;
            }
        };

        $this->assertEquals(C::XS_ANY_NS_OTHER, $c->getAttributeNamespace());
    }


    /**
     */
    public function testOtherNamespacePassingLocalThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$local]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return C::XS_ANY_NS_OTHER;
            }
        };
    }


    /**
     */
    public function testTargetNamespacePassingTargetSucceeds(): void
    {
        $c = new class ([self::$target]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return C::XS_ANY_NS_TARGET;
            }
        };

        $this->assertEquals(C::XS_ANY_NS_TARGET, $c->getAttributeNamespace());
    }


    /**
     */
    public function testTargetNamespacePassingTargetArraySucceeds(): void
    {
        $c = new class ([self::$target]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return [C::XS_ANY_NS_TARGET];
            }
        };

        $this->assertEquals([C::XS_ANY_NS_TARGET], $c->getAttributeNamespace());
    }


    /**
     */
    public function testTargetNamespacePassingTargetArraySucceedsWithLocal(): void
    {
        $c = new class ([self::$target]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return [C::XS_ANY_NS_TARGET, C::XS_ANY_NS_LOCAL];
            }
        };

        $this->assertEquals([C::XS_ANY_NS_TARGET, C::XS_ANY_NS_LOCAL], $c->getAttributeNamespace());
    }


    /**
     */
    public function testTargetNamespacePassingOtherThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$other]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return C::XS_ANY_NS_TARGET;
            }
        };
    }


    /**
     */
    public function testLocalNamespacePassingLocalSucceeds(): void
    {
        $c = new class ([self::$local]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return C::XS_ANY_NS_LOCAL;
            }
        };

        $this->assertEquals(C::XS_ANY_NS_LOCAL, $c->getAttributeNamespace());
    }


    /**
     */
    public function testLocalNamespacePassingLocalArraySucceeds(): void
    {
        $c = new class ([self::$local]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return [C::XS_ANY_NS_LOCAL];
            }
        };

        $this->assertEquals([C::XS_ANY_NS_LOCAL], $c->getAttributeNamespace());
    }


    /**
     */
    public function testLocalNamespacePassingTargetThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$target]) extends ExtendableAttributesElement {
            public function getAtributeNamespace(): array|string
            {
                return C::XS_ANY_NS_LOCAL;
            }
        };
    }



    /**
     */
    public function testLocalNamespacePassingOtherThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$other]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return C::XS_ANY_NS_LOCAL;
            }
        };
    }


    /**
     */
    public function testAnyNamespacePassingTargetSucceeds(): void
    {
        $c = new class ([self::$target]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return C::XS_ANY_NS_ANY;
            }
        };

        $this->assertEquals(C::XS_ANY_NS_ANY, $c->getAttributeNamespace());
    }


    /**
     */
    public function testAnyNamespacePassingOtherSucceeds(): void
    {
        $c = new class ([self::$other]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return C::XS_ANY_NS_ANY;
            }
        };

        $this->assertEquals(C::XS_ANY_NS_ANY, $c->getAttributeNamespace());
    }
}
