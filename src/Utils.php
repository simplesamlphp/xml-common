<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use InvalidArgumentException;
use SimpleSAML\Assert\Assert;

/**
 * Helper functions for the XML library.
 *
 * @package simplesamlphp/xml-common
 */
class Utils
{
    /**
     * Do an XPath query on an XML node.
     *
     * @param \DOMNode $node  The XML node.
     * @param string $query The query.
     * @return \DOMNode[] Array with matching DOM nodes.
     */
    public static function xpQuery(DOMNode $node, string $query): array
    {
        static $xpCache = null;

        if ($node instanceof DOMDocument) {
            $doc = $node;
        } else {
            $doc = $node->ownerDocument;
            Assert::notNull($doc);
            /** @psalm-var \DOMDocument $doc */
        }

        if ($xpCache === null || !$xpCache->document->isSameNode($doc)) {
            $xpCache = new DOMXPath($doc);
            $xpCache->registerNamespace('soap-env', Constants::NS_SOAP);
            $xpCache->registerNamespace('saml_protocol', Constants::NS_SAMLP);
            $xpCache->registerNamespace('saml_assertion', Constants::NS_SAML);
            $xpCache->registerNamespace('saml_metadata', Constants::NS_MD);
            $xpCache->registerNamespace('ds', Constants::NS_XDSIG);
            $xpCache->registerNamespace('xenc', Constants::NS_XENC);
        }

        $results = $xpCache->query($query, $node);
        $ret = [];
        for ($i = 0; $i < $results->length; $i++) {
            $ret[$i] = $results->item($i);
        }

        return $ret;
    }


    /**
     * Make an exact copy the specific \DOMElement.
     *
     * @param \DOMElement $element The element we should copy.
     * @param \DOMElement|null $parent The target parent element.
     * @return \DOMElement The copied element.
     */
    public static function copyElement(DOMElement $element, DOMElement $parent = null): DOMElement
    {
        if ($parent === null) {
            $document = DOMDocumentFactory::create();
        } else {
            $document = $parent->ownerDocument;
            Assert::notNull($document);
            /** @psalm-var \DOMDocument $document */
        }

        $namespaces = [];
        for ($e = $element; $e instanceof DOMNode; $e = $e->parentNode) {
            foreach (Utils::xpQuery($e, './namespace::*') as $ns) {
                $prefix = $ns->localName;
                if ($prefix === 'xml' || $prefix === 'xmlns') {
                    continue;
                }
                $uri = $ns->nodeValue;
                if (!isset($namespaces[$prefix])) {
                    $namespaces[$prefix] = $uri;
                }
            }
        }

        /** @var \DOMElement $newElement */
        $newElement = $document->importNode($element, true);
        if ($parent !== null) {
            /* We need to append the child to the parent before we add the namespaces. */
            $parent->appendChild($newElement);
        }

        foreach ($namespaces as $prefix => $uri) {
            $newElement->setAttributeNS($uri, $prefix . ':__ns_workaround__', 'tmp');
            $newElement->removeAttributeNS($uri, '__ns_workaround__');
        }

        return $newElement;
    }


    /**
     * Extract localized strings from a set of nodes.
     *
     * @param \DOMElement $parent The element that contains the localized strings.
     * @param string $namespaceURI The namespace URI the localized strings should have.
     * @param string $localName The localName of the localized strings.
     * @return array Localized strings.
     */
    public static function extractLocalizedStrings(\DOMElement $parent, string $namespaceURI, string $localName): array
    {
        $ret = [];
        foreach ($parent->childNodes as $node) {
            if ($node->namespaceURI !== $namespaceURI || $node->localName !== $localName) {
                continue;
            } elseif (!($node instanceof DOMElement)) {
                continue;
            }

            if ($node->hasAttribute('xml:lang')) {
                $language = $node->getAttribute('xml:lang');
            } else {
                $language = 'en';
            }
            $ret[$language] = trim($node->textContent);
        }

        return $ret;
    }


    /**
     * Extract strings from a set of nodes.
     *
     * @param \DOMElement $parent The element that contains the localized strings.
     * @param string $namespaceURI The namespace URI the string elements should have.
     * @param string $localName The localName of the string elements.
     * @return array The string values of the various nodes.
     */
    public static function extractStrings(DOMElement $parent, string $namespaceURI, string $localName): array
    {
        $ret = [];
        foreach ($parent->childNodes as $node) {
            if ($node->namespaceURI !== $namespaceURI || $node->localName !== $localName) {
                continue;
            }
            $ret[] = trim($node->textContent);
        }

        return $ret;
    }


    /**
     * Append string element.
     *
     * @param \DOMElement $parent The parent element we should append the new nodes to.
     * @param string $namespace The namespace of the created element.
     * @param string $name The name of the created element.
     * @param string $value The value of the element.
     * @return \DOMElement The generated element.
     */
    public static function addString(
        DOMElement $parent,
        string $namespace,
        string $name,
        string $value
    ): DOMElement {
        $doc = $parent->ownerDocument;
        Assert::notNull($doc);
        /** @psalm-var \DOMDocument $doc */

        $n = $doc->createElementNS($namespace, $name);
        $n->appendChild($doc->createTextNode($value));
        $parent->appendChild($n);

        return $n;
    }


    /**
     * Append string elements.
     *
     * @param \DOMElement $parent The parent element we should append the new nodes to.
     * @param string $namespace The namespace of the created elements
     * @param string $name The name of the created elements
     * @param bool $localized Whether the strings are localized, and should include the xml:lang attribute.
     * @param array $values The values we should create the elements from.
     */
    public static function addStrings(
        DOMElement $parent,
        string $namespace,
        string $name,
        bool $localized,
        array $values
    ): void {
        $doc = $parent->ownerDocument;
        Assert::notNull($doc);
        /** @psalm-var \DOMDocument $doc */

        foreach ($values as $index => $value) {
            $n = $doc->createElementNS($namespace, $name);
            $n->appendChild($doc->createTextNode($value));
            if ($localized) {
                $n->setAttribute('xml:lang', $index);
            }
            $parent->appendChild($n);
        }
    }


    /**
     * This function converts a SAML2 timestamp on the form
     * yyyy-mm-ddThh:mm:ss(\.s+)?Z to a UNIX timestamp. The sub-second
     * part is ignored.
     *
     * Andreas comments:
     *  I got this timestamp from Shibboleth 1.3 IdP: 2008-01-17T11:28:03.577Z
     *  Therefore I added to possibility to have microseconds to the format.
     * Added: (\.\\d{1,3})? to the regex.
     *
     * Note that we always require a 'Z' timezone for the dateTime to be valid.
     * This is not in the SAML spec but that's considered to be a bug in the
     * spec. See https://github.com/simplesamlphp/saml2/pull/36 for some
     * background.
     *
     * @param string $time The time we should convert.
     * @throws \Exception
     * @return int Converted to a unix timestamp.
     */
    public static function xsDateTimeToTimestamp(string $time): int
    {
        $matches = [];

        // We use a very strict regex to parse the timestamp.
        $regex = '/^(\\d\\d\\d\\d)-(\\d\\d)-(\\d\\d)T(\\d\\d):(\\d\\d):(\\d\\d)(?:\\.\\d{1,9})?Z$/D';
        if (preg_match($regex, $time, $matches) == 0) {
            throw new InvalidArgumentException(
                'Invalid SAML2 timestamp passed to xsDateTimeToTimestamp: ' . $time
            );
        }

        // Extract the different components of the time from the  matches in the regex.
        // intval will ignore leading zeroes in the string.
        $year   = intval($matches[1]);
        $month  = intval($matches[2]);
        $day    = intval($matches[3]);
        $hour   = intval($matches[4]);
        $minute = intval($matches[5]);
        $second = intval($matches[6]);

        // We use gmmktime because the timestamp will always be given
        //in UTC.
        $ts = gmmktime($hour, $minute, $second, $month, $day, $year);

        return $ts;
    }
}
