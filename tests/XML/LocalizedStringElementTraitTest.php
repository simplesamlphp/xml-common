<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\LocalizedStringElement;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\LocalizedStringElementTrait;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\XML\LocalizedStringElementTraitTest
 *
 * @package simplesamlphp\xml-common
 */
#[CoversClass(SerializableElementTestTrait::class)]
#[CoversClass(LocalizedStringElementTrait::class)]
#[CoversClass(AbstractElement::class)]
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
}
