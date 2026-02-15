<?php

declare(strict_types=1);

use SimpleSAML\Test\Container\TestContainer;
use SimpleSAML\XML\Container\TestContainerSingleton;
use SimpleSAML\XML\Registry\ElementRegistry;

$projectRoot = dirname(__DIR__);
require_once($projectRoot . '/vendor/autoload.php');

$registry = ElementRegistry::getInstance();
$registry->importFromFile(dirname(__FILE__, 2) . '/classes/element.registry.php');

$testContainer = new TestContainer();
TestContainerSingleton::setContainer($testContainer);
