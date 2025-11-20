<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\Helper\Base64BinaryElement;
use SimpleSAML\Test\Helper\BooleanElement;
use SimpleSAML\Test\Helper\StringElement;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\Exception\IOException;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;

/**
 * Class \SimpleSAML\XML\SchemaValidatableElementTraitTest
 *
 * @package simplesamlphp\xml-common
 */
final class SchemaValidatableElementTraitTest extends TestCase
{
    #[DoesNotPerformAssertions]
    public function testSchemaValidationPasses(): void
    {
        $file = 'tests/resources/xml/ssp_StringElement.xml';
        $chunk = DOMDocumentFactory::fromFile($file);

        $document = StringElement::schemaValidate($chunk);
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
