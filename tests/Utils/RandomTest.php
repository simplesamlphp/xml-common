<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Utils;

use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Utils\Random;

use function strlen;

/**
 * Tests for SimpleSAML\XML\Utils\Random.
 *
 * @covers \SimpleSAML\XML\Utils\Random
 */
class RandomTest extends TestCase
{
    /**
     * Test for SimpleSAML\XML\Utils\Random::generateID().
     */
    public function testGenerateID(): void
    {
        $randomUtils = new Random();

        // check that it always starts with an underscore
        $this->assertStringStartsWith('_', $randomUtils->generateID());

        // check the length
        $this->assertEquals(Random::ID_LENGTH, strlen($randomUtils->generateID()));
    }
}
