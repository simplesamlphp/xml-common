<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\Test\XML\ExtendableElement;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\ElementInterface;

use function dirname;

/**
 * Class \SimpleSAML\XML\ExtendableElementTraitTest
 *
 * @covers \SimpleSAML\XML\TestUtils\SchemaValidationTestTrait
 * @covers \SimpleSAML\XML\TestUtils\SerializableElementTestTrait
 * @covers \SimpleSAML\XML\ExtendableElementTrait
 *
 * @package simplesamlphp\xml-common
 */
final class ExtendableElementTraitTest extends TestCase
{
    /** @var \SimpleSAML\XML\ElementInterface */
    protected static ElementInterface $empty;

    /** @var \SimpleSAML\XML\ElementInterface */
    protected static ElementInterface $local;

    /** @var \SimpleSAML\XML\ElementInterface */
    protected static ElementInterface $other;

    /** @var \SimpleSAML\XML\ElementInterface */
    protected static ElementInterface $target;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$empty = new Chunk(DOMDocumentFactory::fromString(<<<XML
            <chunk/>
XML
        )->documentElement);

        self::$local = new Chunk(DOMDocumentFactory::fromString(<<<XML
            <chunk>some</chunk>
XML
        )->documentElement);

        self::$target = new Chunk(DOMDocumentFactory::fromString(<<<XML
            <ssp:chunk xmlns:ssp="urn:x-simplesamlphp:namespace">some</ssp:chunk>
XML
        )->documentElement);

        self::$other = new Chunk(DOMDocumentFactory::fromString(<<<XML
            <dummy:chunk xmlns:dummy="urn:custom:dummy">some</dummy:chunk>
XML
        )->documentElement);
    }


    /**
     */
    public function testInvalidNamespaceThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([]) extends ExtendableElement {
            public function getElementNamespace(): array|string
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
        new class ([]) extends ExtendableElement {
            public function getElementNamespace(): array|string
            {
                return [C::XS_ANY_NS_OTHER, C::XS_ANY_NS_ANY];
            }
        };
    }


    /**
     */
    public function testEmptyNamespaceArrayThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([]) extends ExtendableElement {
            public function getElementNamespace(): array|string
            {
                return [];
            }
        };
    }


    /**
     */
    public function testOtherNamespacePassingOtherSucceeds(): void
    {
        $c = new class ([self::$other]) extends ExtendableElement {
            public function getElementNamespace(): array|string
            {
                return C::XS_ANY_NS_OTHER;
            }
        };

        $this->assertEquals(C::XS_ANY_NS_OTHER, $c->getElementNamespace());
    }


    /**
     */
    public function testOtherNamespacePassingLocalThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$local]) extends ExtendableElement {
            public function getElementNamespace(): array|string
            {
                return C::XS_ANY_NS_OTHER;
            }
        };
    }


    /**
     */
    public function testTargetNamespacePassingTargetSucceeds(): void
    {
        $c = new class ([self::$target]) extends ExtendableElement {
            public function getElementNamespace(): array|string
            {
                return C::XS_ANY_NS_TARGET;
            }
        };

        $this->assertEquals(C::XS_ANY_NS_TARGET, $c->getElementNamespace());
    }


    /**
     */
    public function testTargetNamespacePassingTargetArraySucceeds(): void
    {
        $c = new class ([self::$target]) extends ExtendableElement {
            public function getElementNamespace(): array|string
            {
                return [C::XS_ANY_NS_TARGET];
            }
        };

        $this->assertEquals([C::XS_ANY_NS_TARGET], $c->getElementNamespace());
    }


    /**
     */
    public function testTargetNamespacePassingTargetArraySucceedsWithLocal(): void
    {
        $c = new class ([self::$target]) extends ExtendableElement {
            public function getElementNamespace(): array|string
            {
                return [C::XS_ANY_NS_TARGET, C::XS_ANY_NS_LOCAL];
            }
        };

        $this->assertEquals([C::XS_ANY_NS_TARGET, C::XS_ANY_NS_LOCAL], $c->getElementNamespace());
    }


    /**
     */
    public function testTargetNamespacePassingOtherThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$other]) extends ExtendableElement {
            public function getElementNamespace(): array|string
            {
                return C::XS_ANY_NS_TARGET;
            }
        };
    }


    /**
     */
    public function testLocalNamespacePassingLocalSucceeds(): void
    {
        $c = new class ([self::$local]) extends ExtendableElement {
            public function getElementNamespace(): array|string
            {
                return C::XS_ANY_NS_LOCAL;
            }
        };

        $this->assertEquals(C::XS_ANY_NS_LOCAL, $c->getElementNamespace());
    }


    /**
     */
    public function testLocalNamespacePassingLocalArraySucceeds(): void
    {
        $c = new class ([self::$local]) extends ExtendableElement {
            public function getElementNamespace(): array|string
            {
                return [C::XS_ANY_NS_LOCAL];
            }
        };

        $this->assertEquals([C::XS_ANY_NS_LOCAL], $c->getElementNamespace());
    }


    /**
     */
    public function testLocalNamespacePassingTargetThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$target]) extends ExtendableElement {
            public function getElementNamespace(): array|string
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
        new class ([self::$other]) extends ExtendableElement {
            public function getElementNamespace(): array|string
            {
                return C::XS_ANY_NS_LOCAL;
            }
        };
    }


    /**
     */
    public function testAnyNamespacePassingLocalSucceeds(): void
    {
        $c = new class ([self::$local]) extends ExtendableElement {
            public function getElementNamespace(): array|string
            {
                return C::XS_ANY_NS_ANY;
            }
        };

        $this->assertEquals(C::XS_ANY_NS_ANY, $c->getElementNamespace());
    }


    /**
     */
    public function testAnyNamespacePassingTargetSucceeds(): void
    {
        $c = new class ([self::$target]) extends ExtendableElement {
            public function getElementNamespace(): array|string
            {
                return C::XS_ANY_NS_ANY;
            }
        };

        $this->assertEquals(C::XS_ANY_NS_ANY, $c->getElementNamespace());
    }


    /**
     */
    public function testAnyNamespacePassingOtherSucceeds(): void
    {
        $c = new class ([self::$other]) extends ExtendableElement {
            public function getElementNamespace(): array|string
            {
                return C::XS_ANY_NS_ANY;
            }
        };

        $this->assertEquals(C::XS_ANY_NS_ANY, $c->getElementNamespace());
    }
}
