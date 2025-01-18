<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\NCNameValue;

/**
 * Class \SimpleSAML\Test\XML\Type\NCNameValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(NCNameValue::class)]
final class NCNameValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $ncname
     */
    #[DataProvider('provideNCName')]
    public function testNCName(bool $shouldPass, string $ncname): void
    {
        try {
            NCNameValue::fromString($ncname);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideNCName(): array
    {
        return [
            'valid' => [true, 'Test'],
            'valid starts with underscore' => [true, '_Test'],
            'valid contains dashes' => [true, '_1950-10-04_10-00'],
            'valid contains dots' => [true, 'Te.st'],
            'valid contains diacriticals' => [true, 'fööbár'],
            'valid prefixed v4 UUID' => [true, '_5425e58e-e799-4884-92cc-ca64ecede32f'],
            'invalid empty string' => [false, ''],
            'invalid contains wildcard' => [false, 'Te*st'],
            'invalid starts with dash' => [false, '-Test'],
            'invalid starts with digit' => [false, '1Test'],
            'invalid contains colon' => [false, 'Te:st'],
            'whitespace collapse' => [true, "foobar\n"],
            'normalization' => [true, ' foobar '],
        ];
    }
}
