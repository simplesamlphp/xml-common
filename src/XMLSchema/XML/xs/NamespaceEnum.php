<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\xs;

enum NamespaceEnum: string
{
    case Any = '##any';
    case Local = '##local';
    case Other = '##other';
    case TargetNamespace = '##targetNamespace';
}
