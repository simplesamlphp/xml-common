<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\SchemaViolationException;
use SimpleSAML\XML\Type\AnyURIValue;

/**
 * Class \SimpleSAML\Test\Type\AnyURIValueTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(AnyURIValue::class)]
final class AnyURIValueTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $uri
     */
    #[DataProvider('provideURI')]
    public function testAnyURI(bool $shouldPass, string $uri): void
    {
        try {
            AnyURIValue::fromString($uri);
            $this->assertTrue($shouldPass);
        } catch (SchemaViolationException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideURI(): array
    {
        return [
            'urn' => [true, 'urn:x-simplesamlphp:phpunit'],
            'same-doc' => [true, '#_53d830ab1be17291a546c95c7f1cdf8d3d23c959e6'],
            'url' => [true, 'https://www.simplesamlphp.org'],
            'diacritical' => [true, 'https://aä.com'],
            'spn' => [true, 'spn:a4cf592f-a64c-46ff-a788-b260f474525b'],
            'typos' => [true, 'https//www.uni.l/en/'],
            'spaces' => [true, 'this is silly'],
            'empty' => [true, ''],
            'azure-common' => [true, 'https://sts.windows.net/{tenantid}/'],
        ];
    }
}
