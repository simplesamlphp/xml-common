<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

enum FormChoiceEnum: string
{
    case Qualified = 'qualified';
    case Unqualified = 'unqualified';
}
