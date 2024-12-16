<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use DOMDocument;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\BooleanElement;
use SimpleSAML\Test\XML\StringElement;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\Exception\IOException;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\SchemaValidatableElementTrait;

/**
 * Class \SimpleSAML\XML\SchemaValidatableElementTraitTest
 *
 * @package simplesamlphp\xml-common
 */
#[CoversClass(SchemaValidatableElementTrait::class)]
final class SchemaValidatableElementTraitTest extends TestCase
{
    public function testSchemaValidationPasses(): void
    {
        $file = 'tests/resources/xml/ssp_StringElement.xml';
        $chunk = DOMDocumentFactory::fromFile($file);

        $document = StringElement::schemaValidate($chunk);
        $this->assertInstanceOf(DOMDocument::class, $document);
    }


    public function testSchemaValidationWrongElementFails(): void
    {
        $file = 'tests/resources/xml/ssp_Base64Element.xml';
        $chunk = DOMDocumentFactory::fromFile($file);

        $this->expectException(SchemaViolationException::class);
        StringElement::schemaValidate($chunk);
    }


    public function testSchemaValidationUnknownSchemaFileFails(): void
    {
        $file = 'tests/resources/xml/ssp_BooleanElement.xml';
        $chunk = DOMDocumentFactory::fromFile($file);

        $this->expectException(IOException::class);
        BooleanElement::schemaValidate($chunk);
    }
}
