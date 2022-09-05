<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\SerializableXMLTestTrait;
use SimpleSAML\Test\XML\LocalizedStringElement;
use SimpleSAML\XML\AbstractXMLElement;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\XMLStringElementTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\XML\LocalizedStringElementTraitTest
 *
 * @covers \SimpleSAML\XML\LocalizedStringElementTrait
 * @covers \SimpleSAML\XML\AbstractXMLElement
 * @covers \SimpleSAML\XML\AbstractSerializableXML
 *
 * @package simplesamlphp\xml-common
 */
final class LocalizedStringElementTraitTest extends TestCase
{
    use SerializableXMLTestTrait;

    /**
     */
    public function setup(): void
    {
        $this->testedClass = LocalizedStringElement::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(dirname(__FILE__)) . '/resources/xml/ssp_LocalizedStringElement.xml',
        );
    }

    /**
     */
    public function testMarshalling(): void
    {
        $localizedStringElement = new LocalizedStringElement('en', 'test');

        $this->assertEquals(
            $this->xmlRepresentation->saveXML($this->xmlRepresentation->documentElement),
            strval($localizedStringElement),
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $localizedStringElement = LocalizedStringElement::fromXML($this->xmlRepresentation->documentElement);

        $this->assertEquals('en', $localizedStringElement->getLanguage());
        $this->assertEquals('test', $localizedStringElement->getContent());
    }
}
