<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\LocalizedStringElement;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\StringElementTrait;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\XML\LocalizedStringElementTraitTest
 *
 * @covers \SimpleSAML\XML\TestUtils\SerializableElementTestTrait
 * @covers \SimpleSAML\XML\LocalizedStringElementTrait
 * @covers \SimpleSAML\XML\AbstractElement
 *
 * @package simplesamlphp\xml-common
 */
final class LocalizedStringElementTraitTest extends TestCase
{
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = LocalizedStringElement::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 2) . '/resources/xml/ssp_LocalizedStringElement.xml',
        );
    }

    /**
     */
    public function testMarshalling(): void
    {
        $localizedStringElement = new LocalizedStringElement('en', 'test');

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($localizedStringElement),
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $localizedStringElement = LocalizedStringElement::fromXML(self::$xmlRepresentation->documentElement);

        $this->assertEquals('en', $localizedStringElement->getLanguage());
        $this->assertEquals('test', $localizedStringElement->getContent());
    }
}
