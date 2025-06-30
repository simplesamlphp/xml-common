<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\Test\Helper\ExtendableElement;
use SimpleSAML\XML\{Chunk, DOMDocumentFactory};
use SimpleSAML\XML\{ElementInterface, ExtendableElementTrait};
use SimpleSAML\XML\TestUtils\{SchemaValidationTestTrait, SerializableElementTestTrait};
use SimpleSAML\XMLSchema\XML\Enumeration\NamespaceEnum;

/**
 * Class \SimpleSAML\XML\ExtendableElementTraitTest
 *
 * @package simplesamlphp\xml-common
 */
#[CoversClass(SchemaValidationTestTrait::class)]
#[CoversClass(SerializableElementTestTrait::class)]
#[CoversClass(ExtendableElementTrait::class)]
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
        new class ([]) extends ExtendableElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getElementNamespace(): array|NamespaceEnum
            {
                return [NamespaceEnum::Other, NamespaceEnum::Any];
            }
        };
    }


    /**
     */
    public function testEmptyNamespaceArrayThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([]) extends ExtendableElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getElementNamespace(): array|NamespaceEnum
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
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getElementNamespace(): array|NamespaceEnum
            {
                return NamespaceEnum::Other;
            }
        };

        $this->assertEquals(NamespaceEnum::Other, $c->getElementNamespace());
    }


    /**
     */
    public function testOtherNamespacePassingLocalThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$local]) extends ExtendableElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getElementNamespace(): array|NamespaceEnum
            {
                return NamespaceEnum::Other;
            }
        };
    }


    /**
     */
    public function testTargetNamespacePassingTargetSucceeds(): void
    {
        $c = new class ([self::$target]) extends ExtendableElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getElementNamespace(): array|NamespaceEnum
            {
                return NamespaceEnum::TargetNamespace;
            }
        };

        $this->assertEquals(NamespaceEnum::TargetNamespace, $c->getElementNamespace());
    }


    /**
     */
    public function testTargetNamespacePassingTargetArraySucceeds(): void
    {
        $c = new class ([self::$target]) extends ExtendableElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getElementNamespace(): array|NamespaceEnum
            {
                return [NamespaceEnum::TargetNamespace];
            }
        };

        $this->assertEquals([NamespaceEnum::TargetNamespace], $c->getElementNamespace());
    }


    /**
     */
    public function testTargetNamespacePassingTargetArraySucceedsWithLocal(): void
    {
        $c = new class ([self::$target]) extends ExtendableElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getElementNamespace(): array|NamespaceEnum
            {
                return [NamespaceEnum::TargetNamespace, NamespaceEnum::Local];
            }
        };

        $this->assertEquals([NamespaceEnum::TargetNamespace, NamespaceEnum::Local], $c->getElementNamespace());
    }


    /**
     */
    public function testTargetNamespacePassingOtherThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$other]) extends ExtendableElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getElementNamespace(): array|NamespaceEnum
            {
                return NamespaceEnum::TargetNamespace;
            }
        };
    }


    /**
     */
    public function testLocalNamespacePassingLocalSucceeds(): void
    {
        $c = new class ([self::$local]) extends ExtendableElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getElementNamespace(): array|NamespaceEnum
            {
                return NamespaceEnum::Local;
            }
        };

        $this->assertEquals(NamespaceEnum::Local, $c->getElementNamespace());
    }


    /**
     */
    public function testLocalNamespacePassingLocalArraySucceeds(): void
    {
        $c = new class ([self::$local]) extends ExtendableElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getElementNamespace(): array|NamespaceEnum
            {
                return [NamespaceEnum::Local];
            }
        };

        $this->assertEquals([NamespaceEnum::Local], $c->getElementNamespace());
    }


    /**
     */
    public function testLocalNamespacePassingTargetThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$target]) extends ExtendableElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getElementNamespace(): array|NamespaceEnum
            {
                return NamespaceEnum::Local;
            }
        };
    }


    /**
     */
    public function testLocalNamespacePassingOtherThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([self::$other]) extends ExtendableElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getElementNamespace(): array|NamespaceEnum
            {
                return NamespaceEnum::Local;
            }
        };
    }


    /**
     */
    public function testAnyNamespacePassingLocalSucceeds(): void
    {
        $c = new class ([self::$local]) extends ExtendableElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getElementNamespace(): array|NamespaceEnum
            {
                return NamespaceEnum::Any;
            }
        };

        $this->assertEquals(NamespaceEnum::Any, $c->getElementNamespace());
    }


    /**
     */
    public function testAnyNamespacePassingTargetSucceeds(): void
    {
        $c = new class ([self::$target]) extends ExtendableElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getElementNamespace(): array|NamespaceEnum
            {
                return NamespaceEnum::Any;
            }
        };

        $this->assertEquals(NamespaceEnum::Any, $c->getElementNamespace());
    }


    /**
     */
    public function testAnyNamespacePassingOtherSucceeds(): void
    {
        $c = new class ([self::$other]) extends ExtendableElement {
            /** @return array<int, NamespaceEnum|string>|NamespaceEnum */
            public function getElementNamespace(): array|NamespaceEnum
            {
                return NamespaceEnum::Any;
            }
        };

        $this->assertEquals(NamespaceEnum::Any, $c->getElementNamespace());
    }
}
