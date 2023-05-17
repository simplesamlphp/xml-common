<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\Assert;
use SimpleSAML\Test\XML\QNamelement;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\Test\XML\QNameElementTraitTest
 *
 * @covers \SimpleSAML\XML\TestUtils\SerializableElementTestTrait
 * @covers \SimpleSAML\XML\QNameElementTrait
 *
 * @package simplesamlphp\xml-common
 */
final class QNameElementTraitTest extends TestCase
{
    use SerializableElementTestTrait;

    /**
     */
    public function setup(): void
    {
        $this->testedClass = QNameElement::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 2) . '/resources/xml/ssp_QNameElement.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $qnameElement = new QNameElement('env:Sender', 'http://www.w3.org/2003/05/soap-envelope');

        $this->assertEquals(
            $this->xmlRepresentation->saveXML($this->xmlRepresentation->documentElement),
            strval($qnameElement)
        );
    }


    /**
     */
    public function testMarshallingNonNamespacedQualifiedName(): void
    {
        $qnameElement = new QNameElement('Sender', null);

        $this->assertEquals(
            '<ssp:QNameElement xmlns:ssp="urn:x-simplesamlphp:namespace">Sender</ssp:QNameElement>',
            strval($qnameElement)
        );
    }


    /**
     */
    public function testMarshallingInvalidQualifiedNameThrowsException(): void
    {
        $this->expectException(SchemaViolationException::class);

        new QNameElement('0:Sender', 'http://www.w3.org/2003/05/soap-envelope');
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $qnameElement = QNameElement::fromXML($this->xmlRepresentation->documentElement);

        $this->assertEquals('env:Sender', $qnameElement->getContent());
        $this->assertEquals('http://www.w3.org/2003/05/soap-envelope', $qnameElement->getContentNamespaceUri());
    }


    /**
     */
    public function testUnmarshallingNonNamepacedQualifiedName(): void
    {
        $qnameElement = QNameElement::fromXML(DOMDocumentFactory::fromString(
            '<ssp:QNameElement xmlns:ssp="urn:x-simplesamlphp:namespace">Sender</ssp:QNameElement>'
        )->documentElement);

        $this->assertEquals('Sender', $qnameElement->getContent());
        $this->assertNull($qnameElement->getContentNamespaceUri());
    }
}
