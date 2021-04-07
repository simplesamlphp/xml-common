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
        $o = new ExtendableElement();

        $this->expectException(AssertionFailedException::class);
        $o->setNamespace('wrong');
    }


    /**
     */
    public function testIllegalNamespaceComboThrowsAnException(): void
    {
        $o = new ExtendableElement();

        $this->expectException(AssertionFailedException::class);
        $o->setNamespace([Constants::XS_ANY_NS_OTHER, Constants::XS_ANY_NS_ANY]);
    }


    /**
     */
    public function testEmptyNamespaceArrayThrowsAnException(): void
    {
        $o = new ExtendableElement();

        $this->expectException(AssertionFailedException::class);
        $o->setNamespace([]);
    }


    /**
     */
    public function testOtherNamespacePassingOtherSucceeds(): void
    {
        $o = new ExtendableElement();
        $o->setNamespace(Constants::XS_ANY_NS_OTHER);

        $o->setElements([$this->other]);
        $this->addToAssertionCount(1);
    }


    /**
     */
    public function testOtherNamespacePassingLocalThrowsAnException(): void
    {
        $o = new ExtendableElement();
        $o->setNamespace(Constants::XS_ANY_NS_OTHER);

        $this->expectException(AssertionFailedException::class);
        $o->setElements([$this->local]);
    }


    /**
     */
    public function testTargetNamespacePassingTargetSucceeds(): void
    {
        $o = new ExtendableElement();
        $o->setNamespace(Constants::XS_ANY_NS_TARGET);

        $o->setElements([$this->target]);
        $this->addToAssertionCount(1);
    }


    /**
     */
    public function testTargetNamespacePassingTargetArraySucceeds(): void
    {
        $o = new ExtendableElement();
        $o->setNamespace([Constants::XS_ANY_NS_TARGET]);

        $o->setElements([$this->target]);
        $this->addToAssertionCount(1);
    }


    /**
     */
    public function testTargetNamespacePassingOtherThrowsAnException(): void
    {
        $o = new ExtendableElement();
        $o->setNamespace(Constants::XS_ANY_NS_TARGET);

        $this->expectException(AssertionFailedException::class);
        $o->setElements([$this->other]);
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
    public function testLocalNamespacePassingTargetThrowsAnException(): void
    {
        $o = new ExtendableElement();
        $o->setNamespace(Constants::XS_ANY_NS_LOCAL);

        $this->expectException(AssertionFailedException::class);
        $o->setElements([$this->target]);
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
    public function testAnyNamespacePassingTargetSucceeds(): void
    {
        $o = new ExtendableElement();
        $o->setNamespace(Constants::XS_ANY_NS_ANY);

        $o->setElements([$this->target]);
        $this->addToAssertionCount(1);
    }


    /**
     */
    public function testAnyNamespacePassingOtherSucceeds(): void
    {
        $o = new ExtendableElement();
        $o->setNamespace(Constants::XS_ANY_NS_ANY);

        $o->setElements([$this->other]);
        $this->addToAssertionCount(1);
    }
}
