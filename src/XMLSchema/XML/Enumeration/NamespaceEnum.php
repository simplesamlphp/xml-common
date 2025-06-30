<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\Enumeration;

enum NamespaceEnum: string
{
    case Any = '##any';
    case Local = '##local';
    case Other = '##other';
    case TargetNamespace = '##targetNamespace';
}
