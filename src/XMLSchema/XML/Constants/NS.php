<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSchema\XML\Constants;

final class NS
{
    public const ANY = '##any';

    public const LOCAL = '##local';

    public const OTHER = '##other';

    public const TARGETNAMESPACE = '##targetNamespace';


    /** @var string[] */
    public static array $PREDEFINED = [
        self::ANY,
        self::LOCAL,
        self::OTHER,
        self::TARGETNAMESPACE,
    ];
}
