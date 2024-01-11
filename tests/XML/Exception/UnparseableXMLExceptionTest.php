<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use LibXMLError;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\UnparseableXMLException;

/**
 * Empty shell class for testing UnparseableXMLException.
 *
 * @package simplesaml/xml-security
 *
 * @covers \SimpleSAML\XML\Exception\UnparseableXMLException
 */
final class UnparseableXMLExceptionTest extends TestCase
{
    /** @var \LibXMLError $libxmlerror */
    protected static LibXMLError $libxmlerror;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$libxmlerror = new LibXMLError();

        // Set error variables
        self::$libxmlerror->level = LIBXML_ERR_ERROR;
        self::$libxmlerror->code = 2;
        self::$libxmlerror->column = 3;
        self::$libxmlerror->message = 'message';
        self::$libxmlerror->file = 'file';
        self::$libxmlerror->line = 99;
    }


    public function testUnparseableXMLException(): void
    {
        $this->expectException(UnparseableXMLException::class);
        $this->expectExceptionMessage('Unable to parse XML - "ERROR[2]": "message" in "file" at line 99 on column 3"');

        // Throw exception
        throw new UnparseableXMLException(self::$libxmlerror);
    }
}
