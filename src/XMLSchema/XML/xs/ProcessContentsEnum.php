<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

enum ProcessContentsEnum: string
{
    case Lax = 'lax';
    case Skip = 'skip';
    case Strict = 'strict';
}
