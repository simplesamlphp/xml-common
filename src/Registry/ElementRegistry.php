<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Registry;

use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\AbstractElement;
use SimpleSAML\XML\Exception\InvalidDOMElementException;
use Symfony\Component\Finder\Finder;

use function array_key_exists;
use function array_merge;
use function dirname;
use function implode;

class ElementRegistry
{
    /** @var \SimpleSAML\XML\Registry\AbstractElementRegistry|null $instance */
    protected static ?AbstractElementRegistry $instance = null;

    /** @var array<string, string> */
    protected array $registry = [];


    final private function __construct()
    {
        // Initialize the registry with all the elements we know
        $classesDir = dirname(__FILE__, 3) . '/vendor/simplesamlphp/composer-xmlprovider-installer/classes';

        $finder = Finder::create()->files()->name('element.registry.*.php')->in($classesDir);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $elements = include($file);
                $this->registry = array_merge($this->registry, $elements);
            }
        }
    }


    public static function getInstance(): AbstractElementRegistry
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }


    /**
     * Register a class that can process a certain XML-element.
     *
     * @param string $class The class name of a class extending AbstractElement.
     */
    public function registerElementHandler(string $class): void
    {
        Assert::subclassOf($class, AbstractElement::class);
        $className = AbstractElement::getClassName($class);
        $key = ($class::NS === null) ? $className : implode(':', [$class::NS, $className]);
        $this->registry[$key] = $class;
    }


    /**
     * Search for a class that implements an $element in the given $namespace.
     *
     * Such classes must have been registered previously by calling registerElementHandler(), and they must
     * extend \SimpleSAML\XML\AbstractElement.
     *
     * @param string|null $namespace The namespace URI for the given element.
     * @param string $element The local name of the element.
     *
     * @return string|null The fully-qualified name of a class extending \SimpleSAML\XML\AbstractElement and
     * implementing support for the given element, or null if no such class has been registered before.
     */
    public function getElementHandler(?string $namespace, string $element): ?string
    {
        Assert::nullOrValidURI($namespace, InvalidDOMElementException::class);
        Assert::validNCName($element, InvalidDOMElementException::class);

        $key = ($namespace === null) ? $element : implode(':', [$namespace, $element]);
        if (array_key_exists($key, $this->registry) === true) {
            return $this->registry[$key];
        }

        return null;
    }
}
