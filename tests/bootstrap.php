<?php

declare(strict_types=1);

$projectRoot = dirname(__DIR__);
require_once($projectRoot . '/vendor/autoload.php');

$registry = \SimpleSAML\XML\Registry\ElementRegistry::getInstance();
$registry->importFromFile(dirname(__FILE__, 2) . '/classes/element.registry.php');
