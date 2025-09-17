<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Registry;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Registry\ElementRegistry;

/**
 * @package simplesamlphp\xml-common
 */
#[CoversClass(ElementRegistry::class)]
#[Group('registry')]
final class ElementRegistryTest extends TestCase
{
    /** @var \SimpleSAML\XML\Registry\ElementRegistry */
    protected static ElementRegistry $registry;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$registry = ElementRegistry::getInstance();
        self::$registry->registerElementHandler('\SimpleSAML\Test\Helper\Element');
    }


    /**
     */
    public function testFetchingHandlerWorks(): void
    {
        $handler = self::$registry->getElementHandler('urn:x-simplesamlphp:namespace', 'Element');
        $this->assertEquals($handler, '\SimpleSAML\Test\Helper\Element');
    }


    /**
     */
    public function testAddingHandlerWorks(): void
    {
        self::$registry->registerElementHandler('\SimpleSAML\Test\Helper\ExtendableElement');
        $handler = self::$registry->getElementHandler('urn:x-simplesamlphp:namespace', 'ExtendableElement');
        $this->assertEquals($handler, '\SimpleSAML\Test\Helper\ExtendableElement');
    }


    /**
     */
    public function testUnknownHandlerReturnsNull(): void
    {
        $handler = self::$registry->getElementHandler('urn:x-simplesamlphp:namespace', 'UnknownElement');
        $this->assertNull($handler);
    }
}
