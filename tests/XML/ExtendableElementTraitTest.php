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
use SimpleSAML\XML\TestUtils\SchemaValidationTestTrait;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;

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
    use SerializableElementTestTrait;
    use SchemaValidationTestTrait;

    /** @var \SimpleSAML\XML\ElementInterface */
    protected ElementInterface $empty;

    /** @var \SimpleSAML\XML\ElementInterface */
    protected ElementInterface $local;

    /** @var \SimpleSAML\XML\ElementInterface */
    protected ElementInterface $other;

    /** @var \SimpleSAML\XML\ElementInterface */
    protected ElementInterface $target;


    /**
     */
    public function setup(): void
    {
        $this->schema = dirname(__FILE__, 2) . '/resources/schemas/simplesamlphp.xsd';

        $this->testedClass = ExtendableElement::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 2) . '/resources/xml/ssp_ExtendableElement.xml',
        );

        $this->empty = new Chunk(DOMDocumentFactory::fromString(<<<XML
            <chunk/>
XML
        )->documentElement);

        $this->local = new Chunk(DOMDocumentFactory::fromString(<<<XML
            <chunk>some</chunk>
XML
        )->documentElement);

        $this->target = new Chunk(DOMDocumentFactory::fromString(<<<XML
            <ssp:chunk xmlns:ssp="urn:x-simplesamlphp:namespace">some</ssp:chunk>
XML
        )->documentElement);

        $this->other = new Chunk(DOMDocumentFactory::fromString(<<<XML
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
        $c = new class ([$this->other]) extends ExtendableElement {
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
        new class ([$this->local]) extends ExtendableElement {
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
        $c = new class ([$this->target]) extends ExtendableElement {
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
        $c = new class ([$this->target]) extends ExtendableElement {
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
        $c = new class ([$this->target]) extends ExtendableElement {
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
        new class ([$this->other]) extends ExtendableElement {
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
        $c = new class ([$this->local]) extends ExtendableElement {
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
        $c = new class ([$this->local]) extends ExtendableElement {
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
        new class ([$this->target]) extends ExtendableElement {
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
        new class ([$this->other]) extends ExtendableElement {
            public function getElementNamespace(): array|string
            {
                return C::XS_ANY_NS_LOCAL;
            }
        };
    }


    /**
     */
    public function testAnyNamespacePassingTargetSucceeds(): void
    {
        $c = new class ([$this->target]) extends ExtendableElement {
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
        $c = new class ([$this->other]) extends ExtendableElement {
            public function getElementNamespace(): array|string
            {
                return C::XS_ANY_NS_ANY;
            }
        };

        $this->assertEquals(C::XS_ANY_NS_ANY, $c->getElementNamespace());
    }
}
