<?php

declare(strict_types=1);

namespace SimpleSAML\Test\Helper;

use DOMDocument;

use function preg_replace;

/**
 * Class \SimpleSAML\Test\XML\XMLDumper
 *
 * @package simplesamlphp/xml-common
 */
final class XMLDumper
{
    public static function dumpDOMDocumentXMLWithBase64Content(DOMDocument $document): string
    {
        /** @var string $dump */
        $dump = $document->saveXML($document->documentElement);
        /** @var string $result */
        $result = preg_replace('/ *[\\r\\n] */', '', $dump);
        return $result;
    }
}
