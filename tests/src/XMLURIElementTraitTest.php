<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\SerializableXMLTestTrait;
use SimpleSAML\Test\XML\XMLURIElement;
use SimpleSAML\Test\XML\XMLDumper;
use SimpleSAML\XML\AbstractXMLElement;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\XMLURIElementTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\XML\XMLURIElementTraitTest
 *
 * @covers \SimpleSAML\XML\XMLURIElementTrait
 * @covers \SimpleSAML\XML\XMLStringElementTrait
 * @covers \SimpleSAML\XML\AbstractXMLElement
 * @covers \SimpleSAML\XML\AbstractSerializableXML
 *
 * @package simplesamlphp\xml-common
 */
final class XMLURIElementTraitTest extends TestCase
{
    use SerializableXMLTestTrait;

    /**
     */
    public function setup(): void
    {
        $this->testedClass = XMLURIElement::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(dirname(__FILE__)) . '/resources/xml/bar_XMLURIElement.xml',
        );
    }

    /**
     */
    public function testMarshalling(): void
    {
        $uriElement = new XMLURIElement('https://simplesamlphp.org/example');

        $this->assertEquals(
            XMLDumper::dumpDOMDocumentXMLWithBase64Content($this->xmlRepresentation),
            strval($uriElement),
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $uriElement = XMLURIElement::fromXML($this->xmlRepresentation->documentElement);

        $this->assertEquals('https://simplesamlphp.org/example', $uriElement->getContent());
    }


    /**
     * @dataProvider provideBase64Cases
     */
    public function testURICases(string $xml): void
    {
        $xmlRepresentation = DOMDocumentFactory::fromString($xml);

        $xmlElement = XMLURIElement::fromXML($xmlRepresentation->documentElement);

        $this->assertStringContainsString($xmlElement->getContent(), $xml);
    }

    public function provideBase64Cases(): array
    {
        return [
            'url' => ['<bar:XMLURIElement xmlns:bar="urn:foo:bar">https://simplesamlphp.org/example</bar:XMLURIElement>'],
            'urn' => ['<bar:XMLURIElement xmlns:bar="urn:foo:bar">urn:x-simplesamlphp:example</bar:XMLURIElement>'],
            'reference' => ['<bar:XMLURIElement xmlns:bar="urn:foo:bar">#_1c9038b8201494b277fe14bfedfcbdd2d24564c7f7</bar:XMLURIElement>'],
        ];
    }
}
