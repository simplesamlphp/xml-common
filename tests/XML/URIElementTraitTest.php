<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\URIElement;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XML\URIElementTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\XML\URIElementTraitTest
 *
 * @package simplesamlphp\xml-common
 */
#[CoversClass(SerializableElementTestTrait::class)]
#[CoversClass(URIElementTrait::class)]
#[CoversClass(AbstractElement::class)]
final class URIElementTraitTest extends TestCase
{
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = URIElement::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 2) . '/resources/xml/ssp_URIElement.xml',
        );
    }

    /**
     */
    public function testMarshalling(): void
    {
        $URIElement = new URIElement('https://example.org');

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($URIElement),
        );
    }
}
