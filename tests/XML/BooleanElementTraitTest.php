<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\BooleanElement;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\BooleanElementTrait;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\XML\BooleanElementTraitTest
 *
 * @package simplesamlphp\xml-common
 */
#[CoversClass(SerializableElementTestTrait::class)]
#[CoversClass(BooleanElementTrait::class)]
#[CoversClass(AbstractElement::class)]
final class BooleanElementTraitTest extends TestCase
{
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = BooleanElement::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 2) . '/resources/xml/ssp_BooleanElement.xml',
        );
    }

    /**
     */
    public function testMarshalling(): void
    {
        $booleanElement = new BooleanElement('true');

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($booleanElement),
        );
    }
}
