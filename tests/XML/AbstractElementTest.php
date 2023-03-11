<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Element;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\Exception\MissingAttributeException;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\XML\AbstractElementTest
 *
 * @covers \SimpleSAML\XML\AbstractElement
 *
 * @package simplesamlphp\xml-common
 */
final class AbstractElementTest extends TestCase
{
    use SerializableElementTestTrait;


    /**
     */
    public function setup(): void
    {
        $this->testedClass = Element::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/ssp_Element.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $element = new Element(2, false, 'text');

        $this->assertEquals(
            $this->xmlRepresentation->saveXML($this->xmlRepresentation->documentElement),
            strval($element),
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $element = Element::fromXML($this->xmlRepresentation->documentElement);

        $this->assertEquals(2, $element->getInteger());
        $this->assertEquals(false, $element->getBoolean());
        $this->assertEquals('text', $element->getString());
    }


    /**
     */
    public function testGetAttributeThrowsExceptionOnMissingAttribute(): void
    {
        $doc = $this->xmlRepresentation->documentElement;
        $doc->removeAttribute('text');

        $this->expectException(MissingAttributeException::class);
        Element::fromXML($doc);
    }


    /**
     */
    public function testGetBooleanAttributeThrowsExceptionOnMissingAttribute(): void
    {
        $doc = $this->xmlRepresentation->documentElement;
        $doc->removeAttribute('boolean');

        $this->expectException(MissingAttributeException::class);
        Element::fromXML($doc);
    }


    /**
     */
    public function testGetIntegerAttributeThrowsExceptionOnMissingAttribute(): void
    {
        $doc = $this->xmlRepresentation->documentElement;
        $doc->removeAttribute('integer');

        $this->expectException(MissingAttributeException::class);
        Element::fromXML($doc);
    }
}
