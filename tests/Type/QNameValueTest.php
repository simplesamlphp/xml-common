<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\QNameValue;

/**
 * Class \SimpleSAML\Test\XML\Type\QNameValueTest
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
    #[DataProvider('provideQName')]
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
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideQName(): array
    {
        return [
            'valid' => [true, 'some:Test'],
            'first part containing dash' => [true, 'som-e:Test'],
            'both parts containing dash' => [true, 'so-me:T-est'],
            'start 2nd part with dash' => [false, 'some:-Test'],
            'start both parts with dash' => [false, '-some:-Test'],
            'no colon' => [true, 'Test'],
            'start with colon' => [false, ':test'],
            'multiple colons' => [false, 'test:test:test'],
            'start with digit' => [false, '1Test'],
            'wildcard' => [false, 'Te*st'],
            'prefixed newline' => [false, "\nsome:Test"],
            'trailing newline' => [false, "some:Test\n"],
        ];
    }
}
