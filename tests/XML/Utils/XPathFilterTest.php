<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML\Utils;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Exception\RuntimeException;
use SimpleSAML\XML\Utils\XPathFilter;

use function microtime;
use function str_repeat;

/**
 * Class \SimpleSAML\Test\XML\Utils\XPathFilterTest
 *
 * @package simplesamlphp\xml-common
 */
#[CoversClass(XPathFilter::class)]
final class XPathFilterTest extends TestCase
{
    /**
     */
    public function testRemoveStringContentsSpeed(): void
    {
        // Measure the time it takes to process a large input, should be less than 1 second
        $start = microtime(true);
        $input = str_repeat('""', 10000) . str_repeat("''", 10000);
        $this->assertEquals($input, XPathFilter::removeStringContents($input));
        $end = microtime(true);
        $this->assertLessThan(1, $end - $start, "Processing time was too long");
    }


    /**
     */
    #[DataProvider('provideStringContents')]
    public function testRemoveStringContents(string $input, string $expected): void
    {
        $this->assertEquals($expected, XPathFilter::removeStringContents($input));
    }


    /**
     * @return array<int, array{0: string, 1: string}>
     */
    public static function provideStringContents(): array
    {
        return [
            // Newline
            ["\n", "\n"], // 0

            // Empty string
            ['', ''],   // 1

            // No quotes
            ['foo', 'foo'], // 2
            ['foo bar', 'foo bar'], //3

            // Empty quotes
            ['""', '""'], //4
            ["''", "''"], //5
            ['"" ""', '"" ""'], //6
            ["'' ''", "'' ''"], //7
            ['"" "" ""', '"" "" ""'], //8
            ["'' '' ''", "'' '' ''"], //9

            // Quoted string
            ['"foo"', '""'], //10
            ["'foo'", "''"], //11

            // Multiple quoted strings
            ['"foo" "bar"', '"" ""'], //12
            ["'foo' 'bar'", "'' ''"], //13

            // Multiple quoted strings with newlines
            ['"foo" "bar"' . "\n" . '"abc"', '"" ""' . "\n" . '""'], //14
            ["'foo' 'bar'\n'abc'", "'' ''\n''"], //15

            // Multiple quoted strings with text
            ['"foo"abc"bar"', '""abc""'], //16
            ["'foo'abc'bar'", "''abc''"], //17
            ["'foo'def'bar'", "''def''"], //18

            // Mixed quotes
            ['"foo" \'bar\'', '"" \'\''], //19
            ["'foo' \"bar\"", "'' \"\""], //20

            // No WS between quotes
            ['"foo""bar"', '""""'], //21
            ["'foo''bar'", "''''"], //22
            ['"foo" "bar" "baz"', '"" "" ""'], //23
            ["'foo' 'bar' 'baz'", "'' '' ''"], //24
            ['"foo" \'"bar" "baz"\' "qux"', '"" \'\' ""'], //25
            ["'foo' \"'bar' 'baz'\" 'qux'", "'' \"\" ''"], //26
            ["'foo' 'bar' 'baz'", "'' '' ''"], //27
            ['"foo" \'"bar" "baz"\' "qux"', '"" \'\' ""'], //28
            ["'foo' \"'bar' 'baz'\" 'qux'", "'' \"\" ''"], //29
        ];
    }


    /**
     */
    public function testFilterXPathFunctionSpeed(): void
    {
        // Measure the time it takes to process a large input, should be less than 1 second
        $start = microtime(true);
        // a + -a * 10000 + space * 10000 + (
        $input = 'a' . str_repeat('-a', 10000) . str_repeat(' ', 10000) . "(";
        $this->expectException(RuntimeException::class);
        XPathFilter::filterXPathFunction($input, ['a']);
        $end = microtime(true);
        $this->assertLessThan(1, $end - $start, "Processing time was too long");

        // Because filterXPathAxis() uses the same regex structure, we don't test it separately
    }


    /**
     * @param string[] $allowedFunctions
     */
    #[DataProvider('provideXPathFunction')]
    public function testFilterXPathFunction(string $input, array $allowedFunctions, ?string $expected = null): void
    {
        if ($expected) {
            // Function must throw an exception
            $this->expectException(RuntimeException::class);
            $this->expectExceptionMessage("Invalid function: '" . $expected . "'");
        } else {
            // Function must not throw an exception
            $this->expectNotToPerformAssertions();
        }
        XPathFilter::filterXPathFunction($input, $allowedFunctions);
    }


    /**
     * @return array<int, array{0: string, 1: string[], 2: ?string}>
     */
    public static function provideXPathFunction(): array
    {
        return [
            // [xpath, allowed functions, expected result (null = OK; string = name of the denied function)]
            ['', ['not'], null],
            ['not()', ['not'], null],
            ['count()', ['bar'], 'count'],
            ['not()', [], 'not'],
            ['count ()', ['foo', 'bar'], 'count'],
            [' count ()', [], 'count'],
            ['-count ()', [], 'count'],
            ['- count ()', [], 'count'],
            ['- (count ())', [], 'count'],
            ['(-count())', [], 'count'],
            ['not(not(),not())', ['not'], null],
            ['not((not()),(not()))', ['not'], null], // 11;
            ['not(not(.),not(""))', ['not'], null], // 12;
            ['not( not(.), not(""))', ['not'], null], // 13;

            ['', [], null],
            ['not(count(),not())', ['not'], 'count'],
            ['not(not(),count())', ['not'], 'count'],
            ['count(not(),not())', ['not'], 'count'],
            ['(count(not(),not()))', ['not'], 'count'],
            ['( count(not(),not()))', ['not'], 'count'],
            ['(count (not(),not()))', ['not'], 'count'],
            ['not((not()),(not()))', [], 'not'],
            ['not(not(.),not(""))', [], 'not'], // 22;
            ['not( not(.), not(""))', [], 'not'],

            ['abc-def', [], ''],
            ['(abc-def)', [], ''],
            ['(abc-def ( ) )', [], 'abc-def'],

            ['abc-def', ['abc', 'def'], null],
            ['(abc-def)', ['abc', 'def'], null],
            ['(abc-def ( ) )', ['abc', 'def'], 'abc-def'],
            ['', ['not'], null],
            ['not()', ['not'], null],
            ['count()', ['bar'], 'count'],
            ['not()', [], 'not'],
            ['count ()', ['foo', 'bar'], 'count'],
            [' count ()', [], 'count'],
            ['-count ()', [], 'count'],
            ['- count ()', [], 'count'],
            ['- (count ())', [], 'count'],
            ['(-count())', [], 'count'],
            ['not(not(),not())', ['not'], null],
            ['not((not()),(not()))', ['not'], null], // 11;
            ['not(not(.),not(""))', ['not'], null], // 12;
            ['not( not(.), not(""))', ['not'], null], // 13;

            ['', [], null],
            ['not(count(),not())', ['not'], 'count'],
            ['not(not(),count())', ['not'], 'count'],
            ['count(not(),not())', ['not'], 'count'],
            ['(count(not(),not()))', ['not'], 'count'],
            ['( count(not(),not()))', ['not'], 'count'],
            ['(count (not(),not()))', ['not'], 'count'],
            ['not((not()),(not()))', [], 'not'],
            ['not(not(.),not(""))', [], 'not'], // 22;
            ['not( not(.), not(""))', [], 'not'],

            ['abc-def', [], ''],
            ['(abc-def)', [], ''],
            ['(abc-def ( ) )', [], 'abc-def'],

            ['abc-def', ['abc', 'def'], null],
            ['(abc-def)', ['abc', 'def'], null],
            ['(abc-def ( ) )', ['abc', 'def'], 'abc-def'],

            // Evil
            ['count(//. | //@* | //namespace::*)', ['not', 'foo', 'bar'], 'count'],

            // Perfectly normal
            ["//ElementToEncrypt[@attribute='value']", ['not', 'foo', 'bar'], null],
            ["/RootElement/ChildElement[@id='123']", ['not', 'foo', 'bar'], null],
            ["not(self::UnwantedNode)", ['not', 'foo', 'bar'], null],
            ["//ElementToEncrypt[not(@attribute='value')]", ['not', 'foo', 'bar'], null],

            // From https://www.w3.org/TR/xmlenc-core1/
            ['self::text()[parent::enc:CipherValue[@Id="example1"]]', ['not', 'text'], null],
            ['self::xenc:EncryptedData[@Id="example1"]', ['not', 'foo', 'bar'], null],

            // count in element name
            ["not(self::count)", ['not', 'foo', 'bar'], null],

            // using "namespace" as a Namespace prefix
            ["//namespace:ElementName", ['not', 'foo', 'bar'], null],

            // count in attribute value
            //["//ElementToEncrypt[@attribute='count()']", ['not', 'foo', 'bar'], null],
        ];
    }


    /**
     * @param string[] $allowedAxes
     */
    #[DataProvider('provideXPathAxis')]
    public function testFilterXPathAxis(string $input, array $allowedAxes, ?string $expected = null): void
    {
        if ($expected) {
            // Function must throw an exception
            $this->expectException(RuntimeException::class);
            $this->expectExceptionMessage("Invalid axis: '" . $expected . "'");
        } else {
            // Function must not throw an exception
            $this->expectNotToPerformAssertions();
        }
        XPathFilter::filterXPathAxis($input, $allowedAxes);
    }


    /**
     * @return array<int, array{0: string, 1: string[], 2: ?string}>
     */
    public static function provideXPathAxis(): array
    {
        return [
            // [xpath, allowed axes, exception (null = OK; string = is name of the denied axis)]
            ['', ['self'], null],
            ['self::', [], 'self'],
            [' self::', [], 'self'],
            [' self ::', [], 'self'],
            ['//self::X', [], 'self'],
            ['./self::', [], 'self'],
            ['namespace:element', [], null],
            ['ancestor-or-self::some-node', ['self'], 'ancestor-or-self'],
            [' ancestor-or-self::some-node', ['self'], 'ancestor-or-self'],
            ['/ancestor-or-self::some-node', ['self'], 'ancestor-or-self'],

            ['self::*/child::price', ['self'], 'child'],

            // Evil
            ['count(//. | //@* | //namespace::*)', ['self', 'foo', 'bar'], 'namespace'],

            // Perfectly normal
            ["//ElementToEncrypt[@attribute='value']", ['self'], null],
            ["/RootElement/ChildElement[@id='123']", ['self'], null],
            ["not(self::UnwantedNode)", ['self'], null],
            ["not(self::UnwantedNode)", [], 'self'],
            ["//ElementToEncrypt[not(@attribute='value')]", ['self'], null],

            // From https://www.w3.org/TR/xmlenc-core1/
            ['self::text()[parent::enc:CipherValue[@Id="example1"]]', ['self', 'parent'], null],
            ['self::text()[parent::enc:CipherValue[@Id="example1"]]', ['self'], 'parent'],
            ['self::text()[parent::enc:CipherValue[@Id="example1"]]', ['parent'], 'self'],
            ['self::xenc:EncryptedData[@Id="example1"]', ['self'], null],
            ['self::xenc:EncryptedData[@Id="example1"]', [], 'self'],

            // namespace in element name
            ["not(self::namespace)", ['self'], null],

            // using "namespace" as a Namespace prefix
            ["//namespace:ElementName", ['self'], null],

            // namespace in attribute value
            // ["//ElementToEncrypt[@attribute='namespace::x']", ['self'], null],
        ];
    }
}
