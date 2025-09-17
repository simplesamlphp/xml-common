<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\Helper\Base64BinaryElement;
use SimpleSAML\Test\Helper\BooleanElement;
use SimpleSAML\Test\Helper\StringElement;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XMLSchema\Exception\InvalidValueTypeException;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;

/**
 * Class \SimpleSAML\XML\TypedTextContentTraitTest
 *
 * @package simplesamlphp\xml-common
 */
final class TypedTextContentTraitTest extends TestCase
{
    public function testTypedContentPassesForString(): void
    {
        $file = 'tests/resources/xml/ssp_StringElement.xml';
        $doc = DOMDocumentFactory::fromFile($file);
        /** @var \DOMElement $elt */
        $elt = $doc->documentElement;

        $stringElt = StringElement::fromXML($elt);
        $this->assertInstanceOf(StringElement::class, $stringElt);
    }


    public function testTypedContentPassesForBoolean(): void
    {
        $file = 'tests/resources/xml/ssp_BooleanElement.xml';
        $doc = DOMDocumentFactory::fromFile($file);
        /** @var \DOMElement $elt */
        $elt = $doc->documentElement;

        $stringElt = BooleanElement::fromXML($elt);
        $this->assertInstanceOf(BooleanElement::class, $stringElt);
    }


    public function testTypedContentFailsForWrongType(): void
    {
        $file = 'tests/resources/xml/ssp_BooleanElement.xml';
        $doc = DOMDocumentFactory::fromFile($file);
        /** @var \DOMElement $elt */
        $elt = $doc->documentElement;
        $elt->textContent = 'not-a-boolean';

        $this->expectException(SchemaViolationException::class);
        BooleanElement::fromXML($elt);
    }


    public function testTypedContentFailsForWrongClass(): void
    {
        // Base64Binary has a TEXTCONTENT_TYPE that makes no sense
        $file = 'tests/resources/xml/ssp_Base64BinaryElement.xml';
        $doc = DOMDocumentFactory::fromFile($file);
        /** @var \DOMElement $elt */
        $elt = $doc->documentElement;

        $this->expectException(InvalidValueTypeException::class);
        Base64BinaryElement::fromXML($elt);
    }
}
