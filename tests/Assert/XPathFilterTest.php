<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Test\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\XML\Assert\Assert as XMLAssert;
use SimpleSAML\XML\Constants as C;

use function str_pad;

/**
 * Class \SimpleSAML\XML\Assert\XPathFilterTest
 *
 * @package simplesamlphp/xml-common
 */
#[CoversClass(XMLAssert::class)]
final class XPathFilterTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $filter
     * @param array<string> $axes
     * @param array<string> $functions
     */
    #[DataProvider('provideXPathFilter')]
    public function testDefaultAllowedXPathFilter(
        bool $shouldPass,
        string $filter,
        array $axes = C::DEFAULT_ALLOWED_AXES,
        array $functions = C::DEFAULT_ALLOWED_FUNCTIONS,
    ): void {
        try {
            XMLAssert::allowedXPathFilter($filter, $axes, $functions);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideXPathFilter(): array
    {
        return [
            // Axes
            'ancestor' => [true, 'ancestor::book'],
            'ancestor-or-self' => [true, 'ancestor-or-self::book'],
            'attribute' => [true, 'attribute::book'],
            'child' => [true, 'child::book'],
            'descendant' => [true, 'descendant::book'],
            'descendant-or-self' => [true, 'descendant-or-self::book'],
            'following' => [true, 'following::book'],
            'following-sibling' => [true, 'following-sibling::book'],
            'namespace' => [false, 'namespace::book'],
            'namespace whitelist' => [true, 'namespace::book', ['namespace']],
            'parent' => [true, 'parent::book'],
            'preceding' => [true, 'preceding::book'],
            'preceding-sibling' => [true, 'preceding-sibling::book'],
            'self' => [true, 'self::book'],

            // Functions
            'boolean' => [false, 'boolean(Data/username/text())'],
            'ceiling' => [false, 'ceiling(//items/item[1]/price)'],
            'concat' => [false, "concat('A', '_', 'B')"],
            'contains' => [false, "contains(//username, 'o')"],
            'count' => [false, "count(//Sales.Order[Sales.Customer_Order/Sales.Customer/Name = 'Jansen'])"],
            'false' => [false, '//Sales.Customer[IsGoldCustomer = false()]'],
            'floor' => [false, 'floor(//items/item[1]/price)'],
            'id' => [false, 'SalesInvoiceLines[id(1)]'],
            'lang' => [false, 'lang("en-US")'],
            'last' => [false, 'last()'],
            'local-name' => [false, 'local-name(SalesInvoiceLines) '],
            'name' => [false, 'name(SalesInvoiceLines)'],
            'namespace-uri' => [false, 'namespace-uri(ReportData)'],
            'normalize-space' => [false, 'normalize-space(" Hello World ")'],
            'not' => [true, "//Sales.Customer[not(Name = 'Jansen')]"],
            'number' => [false, 'number("123")'],
            'position' => [false, 'position()'],
            'round' => [false, 'round(//items/item[1]/price)'],
            'starts-with' => [false, "//Sales.Customer[starts-with(Name, 'Jans')]"],
            'string' => [false, 'string(123)'],
            'string-length' => [false, 'string-length(//email)string-length(//email)'],
            'substring' => [false, "/bookstore/book[substring(title,1,5)='Harry']"],
            'substring-after' => [false, "/bookstore/book[substring-after(title,1,5)='Harry']"],
            'substring-before' => [false, "/bookstore/book[substring-before(title,1,5)='Harry']"],
            'sum' => [false, 'sum(//Sales.Order/TotalPrice)'],
            'text' => [false, '//lastname/text()'],
            'translate' => [false, "translate(//email, '@', '_')"],
            'true' => [false, '//Sales.Customer[IsGoldCustomer = true()]'],

            // Edge-cases
            'unknown axis' => [false, 'unknown::book'],
            'unknown function' => [false, 'unknown()'],
            'too long' => [false, str_pad('a', 120, 'a')],
        ];
    }
}
