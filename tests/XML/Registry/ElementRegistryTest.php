<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Registry;

use PHPUnit\Framework\Attributes\{CoversClass, Group};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Registry\ElementRegistry;

use function dirname;
use function sprintf;

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
     * Test that the class-name can be resolved and it's localname matches.
     */
    public function testValidateElementRegistry(): void
    {
        $elementRegistry = dirname(__FILE__, 4) . '/classes/element.registry.php';

        $namespaces = include($elementRegistry);
        foreach ($namespaces as $namespaceURI => $elements) {
            foreach ($elements as $localName => $fqdn) {
                $this->assertTrue(class_exists($fqdn), sprintf('Class \'%s\' could not be found.', $fqdn));
                $this->assertEquals($fqdn::getLocalName(), $localName);
                $this->assertEquals($fqdn::getNamespaceURI(), $namespaceURI);
            }
        }
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
