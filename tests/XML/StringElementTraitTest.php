<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\SerializableElementTestTrait;
use SimpleSAML\Test\XML\StringElement;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\StringElementTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\XML\StringElementTraitTest
 *
 * @covers \SimpleSAML\XML\StringElementTrait
 * @covers \SimpleSAML\XML\AbstractElement
 *
 * @package simplesamlphp\xml-common
 */
final class StringElementTraitTest extends TestCase
{
    use SerializableElementTestTrait;

    /**
     */
    public function setup(): void
    {
        $this->testedClass = StringElement::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 2) . '/resources/xml/ssp_StringElement.xml',
        );
    }

    /**
     */
    public function testMarshalling(): void
    {
        $stringElement = new StringElement('test');

        $this->assertEquals(
            $this->xmlRepresentation->saveXML($this->xmlRepresentation->documentElement),
            strval($stringElement),
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $stringElement = StringElement::fromXML($this->xmlRepresentation->documentElement);

        $this->assertEquals('test', $stringElement->getContent());
    }
}
