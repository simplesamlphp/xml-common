<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use SimpleSAML\XML\QNameElementTrait;
use SimpleSAML\XML\AbstractElement;

/**
 * Empty shell class for testing QNameElement.
 *
 * @package simplesaml/xml-common
 */
final class QNameElement extends AbstractElement
{
    use QNameElementTrait;

    /** @var string */
    public const NS = 'urn:x-simplesamlphp:namespace';

    /** @var string */
    public const NS_PREFIX = 'ssp';


    /**
     * @param string $qname
     * @param string|null $namespaceUri
     */
    public function __construct(string $qname, ?string $namespaceUri = null)
    {
        $this->setContent($qname);
        $this->setContentNamespaceUri($namespaceUri);
    }
}
