<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, DataProviderExternal, DependsOnClass};
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\Assert\QNameTest;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\QNameValue;

use function strval;

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
    #[DataProvider('provideInvalidQName')]
    #[DataProvider('provideValidQName')]
    #[DataProviderExternal(QNameTest::class, 'provideValidQName')]
    #[DependsOnClass(QNameTest::class)]
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
     */
    #[DependsOnClass(QNameTest::class)]
    public function testHelpers(): void
    {
        $qn = QNameValue::fromString('some:Test');
        $this->assertEquals(strval($qn->getNamespacePrefix()), 'some');
        $this->assertEquals(strval($qn->getLocalName()), 'Test');

        $qn = QNameValue::fromString('Test');
        $this->assertNull($qn->getNamespacePrefix());
        $this->assertEquals(strval($qn->getLocalName()), 'Test');
    }


    /**
     * @return array<string, array{0: true, 1: string}>
     */
    public static function provideValidQName(): array
    {
        return [
            'prefixed newline' => [true, "\nsome:Test"],
            'trailing newline' => [true, "some:Test\n"],
        ];
    }


    /**
     * @return array<string, array{0: false, 1: string}>
     */
    public static function provideInvalidQName(): array
    {
        return [
            'start 2nd part with dash' => [false, 'some:-Test'],
            'start both parts with dash' => [false, '-some:-Test'],
            'start with colon' => [false, ':test'],
            'multiple colons' => [false, 'test:test:test'],
            'start with digit' => [false, '1Test'],
            'wildcard' => [false, 'Te*st'],
        ];
    }
}
