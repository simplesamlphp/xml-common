<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use DOMDocument;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\Exception\IOException;
use SimpleSAML\XML\SchemaValidatableElementTrait;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;

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


    public function testSchemaValidationFails(): void
    {
        $file = 'tests/resources/xml/invalid_ExtendableElement.xml';
        $chunk = DOMDocumentFactory::fromFile($file);

        $this->expectException(SchemaViolationException::class);
        $document = StringElement::schemaValidate($chunk);
    }


    public function testSchemaValidationWrongElementFails(): void
    {
        $file = 'tests/resources/xml/ssp_Base64BinaryElement.xml';
        $chunk = DOMDocumentFactory::fromFile($file);

        $this->expectException(SchemaViolationException::class);
        BooleanElement::schemaValidate($chunk);
    }


    public function testSchemaValidationUnknownSchemaFileFails(): void
    {
        $file = 'tests/resources/xml/ssp_Base64BinaryElement.xml';
        $chunk = DOMDocumentFactory::fromFile($file);

        $this->expectException(IOException::class);
        Base64BinaryElement::schemaValidate($chunk);
    }
}
