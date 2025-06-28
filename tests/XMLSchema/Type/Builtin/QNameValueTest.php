<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Builtin;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Builtin\QNameValue;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\Builtin\QNameValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(QNameValue::class)]
final class QNameValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $qname
     */
    #[DataProvider('provideInvalidQName')]
    #[DataProvider('provideValidQName')]
    public function testQName(bool $shouldPass, string $qname): void
    {
        try {
            QNameValue::fromString($qname);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidQName(): array
    {
        return [
            'valid' => [true, '{urn:x-simplesamlphp:namespace}ssp:Chunk'],
            'valid without namespace' => [true, '{urn:x-simplesamlphp:namespace}Chunk'],
            // both parts can contain a dash
            '1st part containing dash' => [true, '{urn:x-simplesamlphp:namespace}s-sp:Chunk'],
            '2nd part containing dash' => [true, '{urn:x-simplesamlphp:namespace}ssp:Ch-unk'],
            'both parts containing dash' => [true, '{urn:x-simplesamlphp:namespace}s-sp:Ch-unk'],
            // A single NCName is also a valid QName
            'no colon' => [true, 'Test'],
            'prefixed newline' => [true, "\nTest"],
            'trailing newline' => [true, "Test\n"],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidQName(): array
    {
        return [
            'empty namespace' => [false, '{}Test'],
            'start 2nd part with dash' => [false, '-Test'],
            'start with colon' => [false, ':test'],
            'multiple colons' => [false, 'test:test:test'],
            'start with digit' => [false, '1Test'],
            'wildcard' => [false, 'Te*st'],
        ];
    }
}
