<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\IOException;

/**
 * Empty shell class for testing IOException.
 *
 * @package simplesaml/xml-security
 */
#[CoversClass(IOException::class)]
final class IOExceptionTest extends TestCase
{
    /**
     */
    public function testIOExceptionDefaultMessage(): void
    {
        $this->expectException(IOException::class);
        $this->expectExceptionMessage('Generic I/O Exception.');

        // Throw exception
        throw new IOException();
    }


    /**
     */
    public function testIOExceptionCustomMessage(): void
    {
        $message = 'Something went wrong.';

        $this->expectException(IOException::class);
        $this->expectExceptionMessage('Something went wrong.');

        // Throw exception
        throw new IOException($message);
    }
}
