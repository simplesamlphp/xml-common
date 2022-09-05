<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\SerializableElementTestTrait;
use SimpleSAML\Test\XML\Base64EStringlement;
use SimpleSAML\Test\XML\XMLDumper;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\Base64StringElementTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\XML\Base64StringElementTraitTest
 *
 * @covers \SimpleSAML\XML\Base64StringElementTrait
 * @covers \SimpleSAML\XML\StringElementTrait
 * @covers \SimpleSAML\XML\AbstractElement
 * @covers \SimpleSAML\XML\AbstractSerializableElement
 *
 * @package simplesamlphp\xml-common
 */
final class Base64StringElementTraitTest extends TestCase
{
    use SerializableElementTestTrait;

    /**
     */
    public function setup(): void
    {
        $this->testedClass = Base64StringElement::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(dirname(__FILE__)) . '/resources/xml/ssp_Base64StringElement.xml',
        );
    }

    /**
     */
    public function testMarshalling(): void
    {
        $base64Element = new Base64StringElement('/CTj03d1DB5e2t7CTo9BEzCf5S9NRzwnBgZRlm32REI=');

        $this->assertEquals(
            XMLDumper::dumpDOMDocumentXMLWithBase64Content($this->xmlRepresentation),
            strval($base64Element),
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $base64Element = Base64StringElement::fromXML($this->xmlRepresentation->documentElement);

        $this->assertEquals('/CTj03d1DB5e2t7CTo9BEzCf5S9NRzwnBgZRlm32REI=', $base64Element->getContent());
    }


    /**
     * @dataProvider provideBase64Cases
     */
    public function testBase64Cases(string $xml): void
    {
        $xmlRepresentation = DOMDocumentFactory::fromString($xml);

        $xmlElement = Base64StringElement::fromXML($xmlRepresentation->documentElement);

        $this->assertStringContainsString($xmlElement->getRawContent(), $xml);
    }

    public function provideBase64Cases(): array
    {
        return [
            'inline' => [
                <<<XML
<ssp:Base64StringElement xmlns:ssp="urn:x-simplesamlphp:namespace">/CTj03d1DB5e2t7CTo9BEzCf5S9NRzwnBgZRlm32REI=</ssp:Base64StringElement>
XML
            ],
            'multiline' => [
                <<<XML
<ssp:Base64StringElement xmlns:ssp="urn:x-simplesamlphp:namespace">
j14G9v6AnsOiEJYgkTg864DG3e/KLqoGpuybPGSGblVTn7ST6M/BsvP7YiVZjLqJEuEvWmf2mW4D
Pb+pbArzzDcsLWEtNveMrw+FkWehDUQV9oe20iepo+W46wmj7zB/eWL+Z8MrGvlycoTndJU6CVwH
TLsB+dq2FDa7JV4pAPjMY32JZTbiwKhzqw3nEi/eVrujJE4YRrlW28D+rXhITfoUAGGvsqPzcwGz
p02lnMe2SmXADY1u9lbVjOhUrJpgvWfn9YuiCR+wjvaGMwIwzfJxChLJZOBV+1ad1CyNTiu6qAbl
xZ4F8cWlMWJ7f0KkWvtw66HOf2VNR6Qan2Ra7Q==
</ssp:Base64StringElement>
XML
            ],
        ];
    }
}
