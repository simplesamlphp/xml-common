<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XMLSchema\Type\Schema;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XMLSchema\Exception\SchemaViolationException;
use SimpleSAML\XMLSchema\Type\Schema\NamespaceListValue;
use SimpleSAML\XMLSchema\XML\Enumeration\NamespaceEnum;

/**
 * Class \SimpleSAML\Test\XMLSchema\Type\Schema\NamespaceListValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(NamespaceListValue::class)]
final class NamespaceListValueTest extends TestCase
{
    /**
     * @param string $namespaceList
     * @param bool $shouldPass
     */
    #[DataProvider('provideNamespaceList')]
    public function testNamespaceListValue(string $namespaceList, bool $shouldPass): void
    {
        try {
            NamespaceListValue::fromString($namespaceList);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * Test helpers
     */
    public function testHelpers(): void
    {
        $x = NamespaceListValue::fromEnum(NamespaceEnum::Any);
        $this->assertEquals(NamespaceEnum::Any, $x->toEnum());

        $y = NameSpaceListValue::fromString('##any');
        $this->assertEquals(NamespaceEnum::Any, $y->toEnum());
    }


    /**
     * @return array<string, array{0: string, 1: bool}>
     */
    public static function provideNamespaceList(): array
    {
        return [
            '##any' => ['##any', true],
            '##any combined' => ['##any urn:x-simplesamlphp:namespace', false],
            '##other' => ['##other', true],
            '##other combined' => ['##other urn:x-simplesamlphp:namespace', false],
            '##local' => ['##local', true],
            '##local combined' => ['##local urn:x-simplesamlphp:namespace', true],
            '##targetNamespace' => ['##targetNamespace', true],
            '##targetNamespace combined' => ['##targetNamespace urn:x-simplesamlphp:namespace', true],
            'multiple spaces and newlines' => [
                "urn:x-simplesamlphp:namespace1  urn:x-simplesamlphp:namespace2 \n urn:x-simplesamlphp:namespace3",
                true,
            ],
            'empty' => ['', false],
        ];
    }
}
