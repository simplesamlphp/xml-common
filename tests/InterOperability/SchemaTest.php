<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\InterOperability;

use DOMDocument;
use DOMElement;
use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XMLSchema\XML\xs\Schema;

use function dirname;

/**
 * Class \SimpleSAML\Test\XMLSchema\SchemaTest
 *
 * @package simplesamlphp\xml-common
 */
final class SchemaTest extends TestCase
{
    private int $failure;


    /**
     * @param \DOMElement $schema;
     */
    #[DataProvider('provideSchema')]
    public function testUnmarshalling(DOMElement $schema): void
    {
        $this->failure = 0;

        try {
            Schema::fromXML($schema);
        } catch (Exception $e) {
            $this->failure = 1;
            $this->assertEquals($this->failure, 0, $e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }

        $this->assertEquals($this->failure, 0);
    }


    /**
     * @return array<string, array<int, DOMElement|null>>
     */
    public static function provideSchema(): array
    {
        $dir = dirname(__FILE__, 3);

        /** @var string $xml */
        $xml = file_get_contents($dir . '/resources/schemas/xml.xsd');
        $xmldoc = new DOMDocument();
        $xmldoc->loadXML($xml);

        /** @var string $xsd */
        $xsd = file_get_contents($dir . '/resources/schemas/XMLSchema.xsd');
        $xsddoc = new DOMDocument();
        $xsddoc->loadXML($xsd);

        /** @var string $xenc */
        $xenc = file_get_contents($dir . '/resources/schemas/xenc-schema.xsd');
        $xencdoc = new DOMDocument();
        $xencdoc->loadXML($xenc);

        /** @var string $xmldsig */
        $xmldsig = file_get_contents($dir . '/resources/schemas/xmldsig-core-schema.xsd');
        $xmldsigdoc = new DOMDocument();
        $xmldsigdoc->loadXML($xmldsig);

        /** @var string $saml2p */
        $saml2p = file_get_contents($dir . '/resources/schemas/saml-schema-protocol-2.0.xsd');
        $saml2pdoc = new DOMDocument();
        $saml2pdoc->loadXML($saml2p);

        /** @var string $saml2a */
        $saml2a = file_get_contents($dir . '/resources/schemas/saml-schema-assertion-2.0.xsd');
        $saml2adoc = new DOMDocument();
        $saml2adoc->loadXML($saml2a);

        /** @var string $saml2m */
        $saml2m = file_get_contents($dir . '/resources/schemas/saml-schema-metadata-2.0.xsd');
        $saml2mdoc = new DOMDocument();
        $saml2mdoc->loadXML($saml2m);

        /** @var string $soapenv11 */
        $soapenv11 = file_get_contents($dir . '/resources/schemas/soap-envelope-1.1.xsd');
        $soapenv11doc = new DOMDocument();
        $soapenv11doc->loadXML($soapenv11);

        return [
            'XML' => [
                $xmldoc->documentElement,
            ],
            'XML Schema' => [
                $xsddoc->documentElement,
            ],
            'XML Encryption' => [
                $xencdoc->documentElement,
            ],
            'XML Signatures' => [
                $xmldsigdoc->documentElement,
            ],
            'SOAP Envelope 1.1' => [
                $soapenv11doc->documentElement,
            ],
            'SAML 2.0 Protocol' => [
                $saml2pdoc->documentElement,
            ],
            'SAML 2.0 Assertion' => [
                $saml2adoc->documentElement,
            ],
            'SAML 2.0 Metadata' => [
                $saml2mdoc->documentElement,
            ],
        ];
    }
}
