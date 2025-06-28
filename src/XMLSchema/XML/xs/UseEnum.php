<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

enum UseEnum: string
{
    case Optional = 'optional';
    case Prohibited = 'prohibited';
    case Required = 'required';
}
