<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Container;

use Dom;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XMLSchema\Type\AnyURIValue;
use SimpleSAML\XMLSchema\XML\Appinfo;

use function array_key_exists;
use function sprintf;

/**
 * One-time instantiation of common elements in XML, for re-use in unit-tests.
 *
 * @package simplesamlphp\xml-common
 * @phpstan-ignore trait.unused
 */
trait XMLSchemaElementsTrait
{
    protected const string SOURCE = 'urn:x-simplesamlphp:source';


    /** @var array<positive-int, \SimpleSAML\XMLSchema\XML\Appinfo> */
    protected array $appinfo = [];

    /** @var array<non-empty-string, \Dom\NodeList> */
    protected array $domText = [];


    /** @param positive-int $x */
    public function getAppinfo(int $x = 1): Appinfo
    {
        if (!array_key_exists($x, $this->appinfo)) {
            $domTextNode = $this->getDOMText(sprintf('Application Information (%d)', $x));

            $this->appinfo[$x] = new Appinfo(
                $domTextNode,
                AnyURIValue::fromString(static::SOURCE),
                [$this->getXMLAttribute($x)],
            );
        }

        return $this->appinfo[$x];
    }


    /** @param non-empty-string $text */
    public function getDOMText(string $text): Dom\NodeList
    {
        if (!array_key_exists($text, $this->domText)) {
            $doc = DOMDocumentFactory::create();

            $elt = $doc->createElement('root');
            $domText = $doc->createTextNode($text);

            $elt->appendChild($domText);

            $this->domText[$text] = $elt->childNodes;
        }

        return $this->domText[$text];
    }
}
