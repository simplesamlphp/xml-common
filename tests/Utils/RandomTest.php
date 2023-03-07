<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Utils;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use SimpleSAML\XML\Utils\Random;

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
        $id = $randomUtils->generateID();

        // check that it always starts with an underscore
        $this->assertStringStartsWith('_', $id);

        $stripped = substr($id, 1);

        // check the length
        $this->assertEquals(36, strlen($stripped));

        // check the pattern
        $this->assertMatchesRegularExpression('/' . Uuid::VALID_PATTERN . '/Dms', $stripped);
    }
}
