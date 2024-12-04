<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\XMLDumper;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\HexBinaryElementTrait;
use SimpleSAML\XML\StringElementTrait;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\XML\HexBinaryElementTraitTest
 *
 * @package simplesamlphp\xml-common
 */
#[CoversClass(SerializableElementTestTrait::class)]
#[CoversClass(HexBinaryElementTrait::class)]
#[CoversClass(StringElementTrait::class)]
#[CoversClass(AbstractElement::class)]
final class HexBinaryElementTraitTest extends TestCase
{
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = HexBinaryElement::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 2) . '/resources/xml/ssp_HexBinaryElement.xml',
        );
    }

    /**
     */
    public function testMarshalling(): void
    {
        $hexBinaryElement = new HexBinaryElement(
            '3f3c6d78206c657673726f693d6e3122302e20226e656f636964676e223d54552d4622383e3f',
        );

        $this->assertEquals(
            XMLDumper::dumpDOMDocumentXMLWithBase64Content(self::$xmlRepresentation),
            strval($hexBinaryElement),
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        /** @var \DOMElement $xml */
        $xml = self::$xmlRepresentation->documentElement;
        $hexBinaryElement = HexBinaryElement::fromXML($xml);

        $this->assertEquals(
            '3f3c6d78206c657673726f693d6e3122302e20226e656f636964676e223d54552d4622383e3f',
            $hexBinaryElement->getContent(),
        );
    }


    /**
     * @param non-empty-string $xml
     */
    #[DataProvider('provideHexBinaryCases')]
    public function testHexBinaryCases(string $xml): void
    {
        $xmlRepresentation = DOMDocumentFactory::fromString($xml);
        /** @var \DOMElement $xmlElement */
        $xmlElement = $xmlRepresentation->documentElement;

        $hexBinary = HexBinaryElement::fromXML($xmlElement);

        $this->assertStringContainsString($hexBinary->getRawContent(), $xml);
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function provideHexBinaryCases(): array
    {
        return [
            'inline' => [
                <<<XML
<ssp:HexBinaryElement xmlns:ssp="urn:x-simplesamlphp:namespace">3f3c6d78206c657673726f693d6e3122302e20226e656f636964676e223d54552d4622383e3f</ssp:HexBinaryElement>
XML
                ,
            ],
            'multiline' => [
                <<<XML
<ssp:HexBinaryElement xmlns:ssp="urn:x-simplesamlphp:namespace">
3f3c6d78206c657673726f693d6e3122302e20226e656f636964676e223d54552d4622383e3f
</ssp:HexBinaryElement>
XML
                ,
            ],
        ];
    }
}
