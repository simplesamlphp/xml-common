<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use Dom;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XMLSchema\Exception\MissingAttributeException;
use SimpleSAML\XMLSchema\Type\BooleanValue;
use SimpleSAML\XMLSchema\Type\IntegerValue;
use SimpleSAML\XMLSchema\Type\StringValue;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\XML\ChunkTest
 *
 * @package simplesamlphp\xml-common
 */
#[CoversClass(Chunk::class)]
final class ChunkTest extends TestCase
{
    use SerializableElementTestTrait;


    public static function setUpBeforeClass(): void
    {
        self::$testedClass = Chunk::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 2) . '/resources/xml/ssp_Element.xml',
        );
    }


    public function testMarshalling(): void
    {
        $xml = self::$xmlRepresentation->documentElement;
        $this->assertInstanceOf(Dom\Element::class, $xml);

        $chunk = new Chunk($xml);

        $representationRoot = self::$xmlRepresentation->documentElement;
        $this->assertInstanceOf(Dom\Element::class, $representationRoot);

        $expectedXml = self::$xmlRepresentation->saveXml($representationRoot);
        $this->assertNotSame('', $expectedXml);
        /** @var non-empty-string $expectedXml */

        $actualXml = strval($chunk);
        $this->assertNotSame('', $actualXml);
        /** @var non-empty-string $actualXml */

        $expectedDoc = DOMDocumentFactory::fromString($expectedXml);
        $actualDoc = DOMDocumentFactory::fromString($actualXml);

        $expectedRoot = $expectedDoc->documentElement;
        $this->assertInstanceOf(Dom\Element::class, $expectedRoot);

        $actualRoot = $actualDoc->documentElement;
        $this->assertInstanceOf(Dom\Element::class, $actualRoot);

        $this->assertSame(
            $expectedRoot->C14N(),
            $actualRoot->C14N(),
        );
    }


    public function testUnmarshalling(): void
    {
        $xml = self::$xmlRepresentation->documentElement;
        $this->assertInstanceOf(Dom\Element::class, $xml);

        $chunk = new Chunk($xml);

        $this->assertEquals($chunk->getLocalName(), 'Element');
        $this->assertEquals($chunk->getNamespaceURI(), 'urn:x-simplesamlphp:namespace');
        $this->assertEquals($chunk->getprefix(), 'ssp');
        $this->assertEquals($chunk->getQualifiedName(), 'ssp:Element');
        $this->assertFalse($chunk->isEmptyElement());

        // Get mandatory attributes
        $this->assertEquals('2', strval($chunk::getAttribute($xml, 'integer', IntegerValue::class)));
        $this->assertEquals('false', strval($chunk::getAttribute($xml, 'boolean', BooleanValue::class)));
        $this->assertEquals('text', strval($chunk::getAttribute($xml, 'text', StringValue::class)));
        $this->assertEquals('otherText', strval($chunk::getAttribute($xml, 'otherText')));

        // Get optional attributes
        $this->assertEquals('text', strval($chunk::getOptionalAttribute($xml, 'text')));
        $this->assertEquals('otherText', strval($chunk::getOptionalAttribute($xml, 'otherText', StringValue::class)));
        $this->assertEquals('false', strval($chunk::getOptionalAttribute($xml, 'boolean', BooleanValue::class)));
        $this->assertEquals('2', strval($chunk::getOptionalAttribute($xml, 'integer', IntegerValue::class)));

        // Get optional non-existing attributes
        $this->assertNull($chunk::getOptionalAttribute($xml, 'non-existing'));
        $this->assertNull($chunk::getOptionalAttribute($xml, 'non-existing', BooleanValue::class));
        $this->assertNull($chunk::getOptionalAttribute($xml, 'non-existing', IntegerValue::class));

        // Get optional non-existing attributes with default
        $this->assertEquals(
            'other text',
            $chunk::getOptionalAttribute(
                $xml,
                'non-existing',
                StringValue::class,
                StringValue::fromString('other text'),
            ),
        );
        $this->assertEquals(
            'true',
            $chunk::getOptionalAttribute(
                $xml,
                'non-existing',
                BooleanValue::class,
                BooleanValue::fromString('true'),
            ),
        );
        $this->assertEquals(
            '3',
            $chunk::getOptionalAttribute(
                $xml,
                'non-existing',
                IntegerValue::class,
                IntegerValue::fromString('3'),
            ),
        );

        // Get mandatory non-existing attributes
        $this->expectException(MissingAttributeException::class);
        $chunk::getAttribute($xml, 'non-existing');
        $chunk::getAttribute($xml, 'non-existing', BooleanValue::class);
        $chunk::getAttribute($xml, 'non-existing', IntegerValue::class);
    }
}
