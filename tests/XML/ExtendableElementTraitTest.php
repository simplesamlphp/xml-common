<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\Test\Helper\ExtendableElement;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\ElementInterface;
use SimpleSAML\XMLSchema\XML\Constants\NS;

/**
 * Class \SimpleSAML\XML\ExtendableElementTraitTest
 *
 * @package simplesamlphp\xml-common
 */
final class ExtendableElementTraitTest extends TestCase
{
    /** @var \SimpleSAML\XML\SerializableElementInterface */
    protected static ElementInterface $empty;

    /** @var \SimpleSAML\XML\SerializableElementInterface */
    protected static ElementInterface $local;

    /** @var \SimpleSAML\XML\SerializableElementInterface */
    protected static ElementInterface $other;

    /** @var \SimpleSAML\XML\SerializableElementInterface */
    protected static ElementInterface $target;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        $emptyDocument = DOMDocumentFactory::fromString(
            <<<XML
            <chunk/>
XML
            ,
        );

        $localDocument = DOMDocumentFactory::fromString(
            <<<XML
            <chunk>some</chunk>
XML
            ,
        );

        $targetDocument = DOMDocumentFactory::fromString(
            <<<XML
            <ssp:chunk xmlns:ssp="urn:x-simplesamlphp:namespace">some</ssp:chunk>
XML
            ,
        );

        $otherDocument = DOMDocumentFactory::fromString(
            <<<XML
            <dummy:chunk xmlns:dummy="urn:custom:dummy">some</dummy:chunk>
XML
            ,
        );

        /** @var \DOMElement $emptyElement */
        $emptyElement = $emptyDocument->documentElement;
        /** @var \DOMElement $localElement */
        $localElement = $localDocument->documentElement;
        /** @var \DOMElement $targetElement */
        $targetElement = $targetDocument->documentElement;
        /** @var \DOMElement $otherElement */
        $otherElement = $otherDocument->documentElement;

        self::$empty = new Chunk($emptyElement);
        self::$local = new Chunk($localElement);
        self::$target = new Chunk($targetElement);
        self::$other = new Chunk($otherElement);
    }


    /**
     */
    public function testIllegalNamespaceComboThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        // @phpstan-ignore expr.resultUnused
        new class ([]) extends ExtendableElement {
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getElementNamespace(): array|string
            {
                return [NS::OTHER, NS::ANY];
            }
        };
    }


    /**
     */
    public function testEmptyNamespaceArrayThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        // @phpstan-ignore expr.resultUnused
        new class ([]) extends ExtendableElement {
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
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
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getElementNamespace(): array|string
            {
                return NS::OTHER;
            }
        };

        $this->assertEquals(NS::OTHER, $c->getElementNamespace());
    }


    /**
     */
    public function testOtherNamespacePassingLocalThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$local]) extends ExtendableElement {
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getElementNamespace(): array|string
            {
                return NS::OTHER;
            }
        };
    }


    /**
     */
    public function testTargetNamespacePassingTargetSucceeds(): void
    {
        $c = new class ([self::$target]) extends ExtendableElement {
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getElementNamespace(): array|string
            {
                return NS::TARGETNAMESPACE;
            }
        };

        $this->assertEquals(NS::TARGETNAMESPACE, $c->getElementNamespace());
    }


    /**
     */
    public function testTargetNamespacePassingTargetArraySucceeds(): void
    {
        $c = new class ([self::$target]) extends ExtendableElement {
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getElementNamespace(): array|string
            {
                return [NS::TARGETNAMESPACE];
            }
        };

        $this->assertEquals([NS::TARGETNAMESPACE], $c->getElementNamespace());
    }


    /**
     */
    public function testTargetNamespacePassingTargetArraySucceedsWithLocal(): void
    {
        $c = new class ([self::$target]) extends ExtendableElement {
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getElementNamespace(): array|string
            {
                return [NS::TARGETNAMESPACE, NS::LOCAL];
            }
        };

        $this->assertEquals([NS::TARGETNAMESPACE, NS::LOCAL], $c->getElementNamespace());
    }


    /**
     */
    public function testTargetNamespacePassingOtherThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$other]) extends ExtendableElement {
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getElementNamespace(): array|string
            {
                return NS::TARGETNAMESPACE;
            }
        };
    }


    /**
     */
    public function testLocalNamespacePassingLocalSucceeds(): void
    {
        $c = new class ([self::$local]) extends ExtendableElement {
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getElementNamespace(): array|string
            {
                return NS::LOCAL;
            }
        };

        $this->assertEquals(NS::LOCAL, $c->getElementNamespace());
    }


    /**
     */
    public function testLocalNamespacePassingLocalArraySucceeds(): void
    {
        $c = new class ([self::$local]) extends ExtendableElement {
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getElementNamespace(): array|string
            {
                return [NS::LOCAL];
            }
        };

        $this->assertEquals([NS::LOCAL], $c->getElementNamespace());
    }


    /**
     */
    public function testLocalNamespacePassingTargetThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$target]) extends ExtendableElement {
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getElementNamespace(): array|string
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
        new class ([self::$other]) extends ExtendableElement {
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getElementNamespace(): array|string
            {
                return NS::LOCAL;
            }
        };
    }


    /**
     */
    public function testAnyNamespacePassingLocalSucceeds(): void
    {
        $c = new class ([self::$local]) extends ExtendableElement {
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getElementNamespace(): array|string
            {
                return NS::ANY;
            }
        };

        $this->assertEquals(NS::ANY, $c->getElementNamespace());
    }


    /**
     */
    public function testAnyNamespacePassingTargetSucceeds(): void
    {
        $c = new class ([self::$target]) extends ExtendableElement {
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getElementNamespace(): array|string
            {
                return NS::ANY;
            }
        };

        $this->assertEquals(NS::ANY, $c->getElementNamespace());
    }


    /**
     */
    public function testAnyNamespacePassingOtherSucceeds(): void
    {
        $c = new class ([self::$other]) extends ExtendableElement {
            /**
             * @return array<int, string>|string
             * @phpstan-ignore return.unusedType
             */
            public function getElementNamespace(): array|string
            {
                return NS::ANY;
            }
        };

        $this->assertEquals(NS::ANY, $c->getElementNamespace());
    }
}
