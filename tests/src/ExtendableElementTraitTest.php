<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

//use InvalidArgumentException;
//use RuntimeException;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\Test\XML\ExtendableElement;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\Constants;
use SimpleSAML\XML\DOMDocumentFactory;
//use SimpleSAML\XML\Exception\UnparseableXmlException;

/**
 * Class \SimpleSAML\XML\ExtendableElementTraitTest
 *
 * @covers \SimpleSAML\XML\ExtendableElementTrait
 *
 * @package simplesamlphp\xml-common
 */
final class ExtendableElementTraitTest extends TestCase
{
    /**
     */
    public function setup(): void
    {
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
    public function testLocalNamespacePassingLocalSucceeds(): void
    {
        $o = new ExtendableElement();
        $o->setNamespace(Constants::XS_ANY_NS_LOCAL);

        $o->setElements([$this->local]);
        $this->addToAssertionCount(1);
    }


    /**
     */
    public function testLocalNamespacePassingLocalArraySucceeds(): void
    {
        $o = new ExtendableElement();
        $o->setNamespace([Constants::XS_ANY_NS_LOCAL]);

        $o->setElements([$this->local]);
        $this->addToAssertionCount(1);
    }


    /**
     */
    public function testLocalNamespacePassingOtherThrowsAnException(): void
    {
        $o = new ExtendableElement();
        $o->setNamespace(Constants::XS_ANY_NS_LOCAL);

        $this->expectException(AssertionFailedException::class);
        $o->setElements([$this->other]);
    }


    /**
     */
    public function testLocalNamespacePassingTargetThrowsAnException(): void
    {
        $o = new ExtendableElement();
        $o->setNamespace(Constants::XS_ANY_NS_LOCAL);

        $this->expectException(AssertionFailedException::class);
        $o->setElements([$this->target]);
    }


    /**
     */
    public function testLocalNamespacePassingNonLocalArrayThrowsAnException(): void
    {
        $o = new ExtendableElement();
        $o->setNamespace([Constants::XS_ANY_NS_LOCAL]);

        $this->expectException(AssertionFailedException::class);
        $o->setElements([$this->target]);
    }
}
