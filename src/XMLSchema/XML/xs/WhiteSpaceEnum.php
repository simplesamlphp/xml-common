<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

enum WhiteSpaceEnum: string
{
    case Collapse = 'collapse';
    case Preserve = 'preserve';
    case Replace = 'replace';
}
