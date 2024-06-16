<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\QNameElementTrait;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\Test\XML\QNameElementTraitTest
 *
 * @package simplesamlphp\xml-common
 */
#[CoversClass(SerializableElementTestTrait::class)]
#[CoversClass(QNameElementTrait::class)]
final class QNameElementTraitTest extends TestCase
{
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = QNameElement::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 2) . '/resources/xml/ssp_QNameElement.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $qnameElement = new QNameElement('env:Sender', 'http://www.w3.org/2003/05/soap-envelope');

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($qnameElement),
        );
    }


    /**
     */
    public function testMarshallingNonNamespacedQualifiedName(): void
    {
        $qnameElement = new QNameElement('Sender', null);

        $this->assertEquals(
            '<ssp:QNameElement xmlns:ssp="urn:x-simplesamlphp:namespace">Sender</ssp:QNameElement>',
            strval($qnameElement),
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
    public function testUnmarshallingNonNamepacedQualifiedName(): void
    {
        $doc = DOMDocumentFactory::fromString(
            '<ssp:QNameElement xmlns:ssp="urn:x-simplesamlphp:namespace">Sender</ssp:QNameElement>',
        );

        /** @var \DOMElement $element */
        $element = $doc->documentElement;
        $qnameElement = QNameElement::fromXML($element);

        $this->assertEquals('Sender', $qnameElement->getContent());
        $this->assertNull($qnameElement->getContentNamespaceUri());
    }
}
