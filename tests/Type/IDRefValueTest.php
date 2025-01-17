<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\IDRefValue;

/**
 * Class \SimpleSAML\Test\XML\Type\IDRefValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(IDRefValue::class)]
final class IDRefValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $idref
     */
    #[DataProvider('provideIDRef')]
    public function testIDRef(bool $shouldPass, string $idref): void
    {
        try {
            IDRefValue::fromString($idref);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideIDRef(): array
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
            'invalid starts with digit' => [false, '1Test'],
            'invalid contains colon' => [false, 'Te:st'],
            'whitespace collapse' => [true, "foobar\n"],
            'normalization' => [true, ' foobar '],
        ];
    }
}
