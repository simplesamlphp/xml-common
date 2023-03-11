<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\Assert;
use SimpleSAML\Test\XML\Base64EStringlement;
use SimpleSAML\Test\XML\XMLDumper;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\Base64ElementTrait;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\XML\Base64ElementTraitTest
 *
 * @covers \SimpleSAML\XML\Base64ElementTrait
 * @covers \SimpleSAML\XML\StringElementTrait
 * @covers \SimpleSAML\XML\AbstractElement
 *
 * @package simplesamlphp\xml-common
 */
final class Base64ElementTraitTest extends TestCase
{
    use SerializableElementTestTrait;

    /**
     */
    public function setup(): void
    {
        $this->testedClass = Base64Element::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/ssp_Base64Element.xml',
        );
    }

    /**
     */
    public function testMarshalling(): void
    {
        $base64Element = new Base64Element('/CTj03d1DB5e2t7CTo9BEzCf5S9NRzwnBgZRlm32REI=');

        $this->assertEquals(
            XMLDumper::dumpDOMDocumentXMLWithBase64Content($this->xmlRepresentation),
            strval($base64Element),
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $base64Element = Base64Element::fromXML($this->xmlRepresentation->documentElement);

        $this->assertEquals('/CTj03d1DB5e2t7CTo9BEzCf5S9NRzwnBgZRlm32REI=', $base64Element->getContent());
    }


    /**
     * @param non-empty-string $xml
     * @dataProvider provideBase64Cases
     */
    public function testBase64Cases(string $xml): void
    {
        $xmlRepresentation = DOMDocumentFactory::fromString($xml);

        $xmlElement = Base64Element::fromXML($xmlRepresentation->documentElement);

        $this->assertStringContainsString($xmlElement->getRawContent(), $xml);
    }

    public static function provideBase64Cases(): array
    {
        return [
            'inline' => [
                <<<XML
<ssp:Base64Element xmlns:ssp="urn:x-simplesamlphp:namespace">/CTj03d1DB5e2t7CTo9BEzCf5S9NRzwnBgZRlm32REI=</ssp:Base64Element>
XML
            ],
            'multiline' => [
                <<<XML
<ssp:Base64Element xmlns:ssp="urn:x-simplesamlphp:namespace">
j14G9v6AnsOiEJYgkTg864DG3e/KLqoGpuybPGSGblVTn7ST6M/BsvP7YiVZjLqJEuEvWmf2mW4D
Pb+pbArzzDcsLWEtNveMrw+FkWehDUQV9oe20iepo+W46wmj7zB/eWL+Z8MrGvlycoTndJU6CVwH
TLsB+dq2FDa7JV4pAPjMY32JZTbiwKhzqw3nEi/eVrujJE4YRrlW28D+rXhITfoUAGGvsqPzcwGz
p02lnMe2SmXADY1u9lbVjOhUrJpgvWfn9YuiCR+wjvaGMwIwzfJxChLJZOBV+1ad1CyNTiu6qAbl
xZ4F8cWlMWJ7f0KkWvtw66HOf2VNR6Qan2Ra7Q==
</ssp:Base64Element>
XML
            ],
        ];
    }
}
