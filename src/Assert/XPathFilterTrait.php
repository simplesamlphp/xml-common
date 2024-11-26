<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Assert;

use Exception;
use InvalidArgumentException;
use SimpleSAML\Assert\Assert as BaseAssert;
use SimpleSAML\XML\Constants as C;

use function in_array;
use function preg_match_all;
use function preg_replace;
use function sprintf;

/**
 * @package simplesamlphp/xml-common
 */
trait XPathFilterTrait
{
    /**
     * Remove the content from all single or double-quoted strings in $input, leaving only quotes.
     * Use possessive quantifiers (i.e. *+ and ++ instead of * and + respectively) to prevent backtracking.
     *
     * '/(["\'])(?:(?!\1).)*+\1/'
     *  (["\'])  # Match a single or double quote and capture it in group 1
     *  (?:      # Start a non-capturing group
     *    (?!    # Negative lookahead
     *      \1   # Match the same quote as in group 1
     *    )      # End of negative lookahead
     *    .      # Match any character (that is not a quote, because of the negative lookahead)
     *  )*+      # Repeat the non-capturing group zero or more times, possessively
     *  \1       # Match the same quote as in group 1
     */
    private static string $regex_xpfilter_remove_strings = '/(["\'])(?:(?!\1).)*+\1/';

    /**
     * Function names are lower-case alpha (i.e. [a-z]) and can contain one or more hyphens,
     * but cannot start or end with a hyphen. To match this, we start with matching one or more
     * lower-case alpha characters, followed by zero or more atomic groups that start with a hyphen
     * and then match one or more lower-case alpha characters. This ensures that the function name
     * cannot start or end with a hyphen, but can contain one or more hyphens.
     * More than one consecutive hyphen does not match.
     *
     * '/([a-z]++(?>-[a-z]++)*+)\s*+\(/'
     * (           # Start a capturing group
     *   [a-z]++   # Match one or more lower-case alpha characters
     *   (?>       # Start an atomic group (no capturing)
     *     -       # Match a hyphen
     *     [a-z]++ # Match one or more lower-case alpha characters, possessively
     *   )*+        # Repeat the atomic group zero or more times,
     * )           # End of the capturing group
     * \s*+        # Match zero or more whitespace characters, possessively
     * \(          # Match an opening parenthesis
     */
    private static string $regex_xpfilter_functions = '/([a-z]++(?>-[a-z]++)*+)\\s*+\\(/';

    /**
     * We use the same rules for matching Axis names as we do for function names.
     * The only difference is that we match the '::' instead of the '('
     * so everything that was said about the regular expression for function names
     * applies here as well.
     *
     * '/([a-z]++(?>-[a-z]++)*+)\s*+::'
     * (           # Start a capturing group
     *   [a-z]++   # Match one or more lower-case alpha characters
     *   (?>       # Start an atomic group (no capturing)
     *     -       # Match a hyphen
     *     [a-z]++ # Match one or more lower-case alpha characters, possessively
     *   )*+       # Repeat the atomic group zero or more times,
     * )           # End of the capturing group
     * \s*+        # Match zero or more whitespace characters, possessively
     * \(          # Match an opening parenthesis
     */
    private static string $regex_xpfilter_axes = '/([a-z]++(?>-[a-z]++)*+)\\s*+::/';


    /***********************************************************************************
     *  NOTE:  Custom assertions may be added below this line.                         *
     *         They SHOULD be marked as `private` to ensure the call is forced         *
     *          through __callStatic().                                                *
     *         Assertions marked `public` are called directly and will                 *
     *          not handle any custom exception passed to it.                          *
     ***********************************************************************************/

    /**
     * Check an XPath expression for allowed axes and functions
     * The goal is preventing DoS attacks by limiting the complexity of the XPath expression by only allowing
     * a select subset of functions and axes.
     * The check uses a list of allowed functions and axes, and throws an exception when an unknown function
     * or axis is found in the $xpath_expression.
     *
     * Limitations:
     * - The implementation is based on regular expressions, and does not employ an XPath 1.0 parser. It may not
     *   evaluate all possible valid XPath expressions correctly and cause either false positives for valid
     *   expressions or false negatives for invalid expressions.
     * - The check may still allow expressions that are not safe, I.e. expressions that consist of only
     *   functions and axes that are deemed "save", but that are still slow to evaluate. The time it takes to
     *   evaluate an XPath expression depends on the complexity of both the XPath expression and the XML document.
     *   This check, however, does not take the XML document into account, nor is it aware of the internals of the
     *   XPath processor that will evaluate the expression.
     * - The check was written with the XPath 1.0 syntax in mind, but should work equally well for XPath 2.0 and 3.0.
     *
     * @param string $value
     * @param array<string> $allowed_axes
     * @param array<string> $allowed_functions
     * @param string $message
     */
    private static function allowedXPathFilter(
        string $value,
        array $allowed_axes = C::DEFAULT_ALLOWED_AXES,
        array $allowed_functions = C::DEFAULT_ALLOWED_FUNCTIONS,
        string $message = '',
    ): void {
        BaseAssert::allString($allowed_axes);
        BaseAssert::allString($allowed_functions);
        BaseAssert::maxLength(
            $value,
            C::XPATH_FILTER_MAX_LENGTH,
            sprintf('XPath Filter exceeds the limit of 100 characters.'),
        );

        $strippedValue = preg_replace(
            self::$regex_xpfilter_remove_strings,
            // Replace the content with two of the quotes that were matched
            "\\1\\1",
            $value,
        );

        if ($strippedValue === null) {
            throw new Exception("Error in preg_replace.");
        }

        /**
         * Check if the $xpath_expression uses an XPath function that is not in the list of allowed functions
         *
         * Look for the function specifier '(' and look for a function name before it.
         * Ignoring whitespace before the '(' and the function name.
         * All functions must match a string on a list of allowed function names
         */
        $matches = [];
        $res = preg_match_all(self::$regex_xpfilter_functions, $strippedValue, $matches);
        if ($res === false) {
            throw new Exception("Error in preg_match_all.");
        }

        // Check that all the function names we found are in the list of allowed function names
        foreach ($matches[1] as $match) {
            if (!in_array($match, $allowed_functions)) {
                throw new InvalidArgumentException(sprintf(
                    $message ?: '\'%s\' is not an allowed XPath function.',
                    $match,
                ));
            }
        }

        /**
         * Check if the $xpath_expression uses an XPath axis that is not in the list of allowed axes
         *
         * Look for the axis specifier '::' and look for a function name before it.
         * Ignoring whitespace before the '::' and the axis name.
         * All axes must match a string on a list of allowed axis names
         */
        $matches = [];
        $res = preg_match_all(self::$regex_xpfilter_axes, $strippedValue, $matches);
        if ($res === false) {
            throw new Exception("Error in preg_match_all.");
        }

        // Check that all the axes names we found are in the list of allowed axes names
        foreach ($matches[1] as $match) {
            if (!in_array($match, $allowed_axes)) {
                throw new InvalidArgumentException(sprintf(
                    $message ?: '\'%s\' is not an allowed XPath axis.',
                    $match,
                ));
            }
        }
    }
}
