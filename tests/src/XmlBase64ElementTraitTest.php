<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\AbstractXMLElement;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\XMLBase64ElementTrait;

/**
 * Class \SimpleSAML\XML\XmlBase64ElementTraitTest
 *
 * @covers \SimpleSAML\XML\XmlBase64ElementTrait
 *
 * @package simplesamlphp\xml-common
 */
final class XmlBase64ElementTraitTest extends TestCase
{
    /**
     * @dataProvider provideBase64Cases
     */
    public function testBase64Cases(string $xml): void
    {
        $xmlRepresentation = DOMDocumentFactory::fromString($xml);

        $xmlElement = XMLBase64Element::fromXML($xmlRepresentation->documentElement);

        $this->assertStringContainsString($xmlElement->getRawContent(), $xml);
    }

    public function provideBase64Cases(): array
    {
        return [
            'inline' => [
                <<<XML
<bar:XMLBase64Element xmlns:bar="foo">/CTj03d1DB5e2t7CTo9BEzCf5S9NRzwnBgZRlm32REI=</bar:XMLBase64Element>
XML
            ],
            'multiline' => [
                <<<XML
<bar:XMLBase64Element xmlns:bar="foo">
j14G9v6AnsOiEJYgkTg864DG3e/KLqoGpuybPGSGblVTn7ST6M/BsvP7YiVZjLqJEuEvWmf2mW4D
Pb+pbArzzDcsLWEtNveMrw+FkWehDUQV9oe20iepo+W46wmj7zB/eWL+Z8MrGvlycoTndJU6CVwH
TLsB+dq2FDa7JV4pAPjMY32JZTbiwKhzqw3nEi/eVrujJE4YRrlW28D+rXhITfoUAGGvsqPzcwGz
p02lnMe2SmXADY1u9lbVjOhUrJpgvWfn9YuiCR+wjvaGMwIwzfJxChLJZOBV+1ad1CyNTiu6qAbl
xZ4F8cWlMWJ7f0KkWvtw66HOf2VNR6Qan2Ra7Q==
</bar:XMLBase64Element>
XML
            ],
        ];
    }
}
