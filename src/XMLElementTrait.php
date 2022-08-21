<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

use DOMElement;
use RuntimeException;
use SimpleSAML\XML\DOMDocumentFactory;

use function array_pop;
use function get_object_vars;

/**
 * Trait grouping common functionality for elements implementing the XMLElement element.
 *
 * @package simplesamlphp/xml-common
 */
trait XMLElementTrait
{
    /**
     * The localName of the element.
     *
     * @var string
     */
    protected string $localName;

    /**
     * The namespaceURI of this element.
     *
     * @var string|null
     */
    protected ?string $namespaceURI;
}
