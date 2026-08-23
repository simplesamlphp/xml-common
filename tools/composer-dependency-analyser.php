<?php

declare(strict_types=1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

return (new Configuration())
    // Constant from libxml (PHP 8.4+ / libxml ≥ 2.13); not a real class
    ->ignoreUnknownClasses([
        'LIBXML_NO_XXE',
    ])

    // Composer plugin – never referenced from PHP source
    ->ignoreErrorsOnPackage(
        'simplesamlphp/composer-xmlprovider-installer',
        [ErrorType::UNUSED_DEPENDENCY]
    );
