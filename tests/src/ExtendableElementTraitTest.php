<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\Test\XML\ExtendableElement;
use SimpleSAML\Test\XML\SerializableXMLTestTrait;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\Constants;
use SimpleSAML\XML\DOMDocumentFactory;

use function dirname;

/**
 * Class \SimpleSAML\XML\ExtendableElementTraitTest
 *
 * @covers \SimpleSAML\XML\ExtendableElementTrait
 *
 * @package simplesamlphp\xml-common
 */
final class ExtendableElementTraitTest extends TestCase
{
    use SerializableXMLTestTrait;

    /** @var \SimpleSAML\XML\XMLElementInterface */
    protected $empty;

    /** @var \SimpleSAML\XML\XMLElementInterface */
    protected $local;

    /** @var \SimpleSAML\XML\XMLElementInterface */
    protected $other;

    /** @var \SimpleSAML\XML\XMLElementInterface */
    protected $target;


    /**
     */
    public function setup(): void
    {
        $this->testedClass = ExtendableElement::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(dirname(__FILE__)) . '/resources/xml/ssp_ExtendableElement.xml'
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
            <ssp:chunk xmlns:ssp="urn:custom:ssp">some</ssp:chunk>
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
            public function getNamespace()
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
            public function getNamespace()
            {
                return [Constants::XS_ANY_NS_OTHER, Constants::XS_ANY_NS_ANY];
            }
        };
    }


    /**
     */
    public function testEmptyNamespaceArrayThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([]) extends ExtendableElement {
            public function getNamespace()
            {
                return [];
            }
        };
    }


    /**
     */
    public function testOtherNamespacePassingOtherSucceeds(): void
    {
        new class ([$this->other]) extends ExtendableElement {
            public function getNamespace()
            {
                return Constants::XS_ANY_NS_OTHER;
            }
        };

        $this->addToAssertionCount(1);
    }


    /**
     */
    public function testOtherNamespacePassingLocalThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([$this->local]) extends ExtendableElement {
            public function getNamespace()
            {
                return Constants::XS_ANY_NS_OTHER;
            }
        };
    }


    /**
     */
    public function testTargetNamespacePassingTargetSucceeds(): void
    {
        new class ([$this->target]) extends ExtendableElement {
            public function getNamespace()
            {
                return Constants::XS_ANY_NS_TARGET;
            }
        };

        $this->addToAssertionCount(1);
    }


    /**
     */
    public function testTargetNamespacePassingTargetArraySucceeds(): void
    {
        new class ([$this->target]) extends ExtendableElement {
            public function getNamespace()
            {
                return [Constants::XS_ANY_NS_TARGET];
            }
        };

        $this->addToAssertionCount(1);
    }


    /**
     */
    public function testTargetNamespacePassingOtherThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([$this->other]) extends ExtendableElement {
            public function getNamespace()
            {
                return Constants::XS_ANY_NS_TARGET;
            }
        };
    }


    /**
     */
    public function testLocalNamespacePassingLocalSucceeds(): void
    {
        $o = new class ([$this->local]) extends ExtendableElement {
            public function getNamespace()
            {
                return Constants::XS_ANY_NS_LOCAL;
            }
        };

        $this->addToAssertionCount(1);
    }


    /**
     */
    public function testLocalNamespacePassingLocalArraySucceeds(): void
    {
        new class ([$this->local]) extends ExtendableElement {
            public function getNamespace()
            {
                return Constants::XS_ANY_NS_LOCAL;
            }
        };

        $this->addToAssertionCount(1);
    }


    /**
     */
    public function testLocalNamespacePassingTargetThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([$this->target]) extends ExtendableElement {
            public function getNamespace()
            {
                return Constants::XS_ANY_NS_LOCAL;
            }
        };
    }


    /**
     */
    public function testLocalNamespacePassingOtherThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([$this->other]) extends ExtendableElement {
            public function getNamespace()
            {
                return Constants::XS_ANY_NS_LOCAL;
            }
        };
    }


    /**
     */
    public function testAnyNamespacePassingTargetSucceeds(): void
    {
        $o = new class ([$this->target]) extends ExtendableElement {
            public function getNamespace()
            {
                return Constants::XS_ANY_NS_ANY;
            }
        };

        $this->addToAssertionCount(1);
    }


    /**
     */
    public function testAnyNamespacePassingOtherSucceeds(): void
    {
        $o = new class ([$this->other]) extends ExtendableElement {
            public function getNamespace()
            {
                return Constants::XS_ANY_NS_ANY;
            }
        };

        $this->addToAssertionCount(1);
    }
}
