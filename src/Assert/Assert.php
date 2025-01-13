<?php

declare(strict_types=1);

namespace SimpleSAML\XML\Assert;

use SimpleSAML\Assert\Assert as BaseAssert;

/**
 * @package simplesamlphp/xml-common
 *
 * @method static void validAllowedXPathFilter(mixed $value, array $allowed_axes, array $allowed_functions, string $message = '', string $exception = '')
 * @method static void validAnyURI(mixed $value, string $message = '', string $exception = '')
 * @method static void validBase64Binary(mixed $value, string $message = '', string $exception = '')
 * @method static void validBoolean(mixed $value, string $message = '', string $exception = '')
 * @method static void validDate(mixed $value, string $message = '', string $exception = '')
 * @method static void validDateTime(mixed $value, string $message = '', string $exception = '')
 * @method static void validDay(mixed $value, string $message = '', string $exception = '')
 * @method static void validDecimal(mixed $value, string $message = '', string $exception = '')
 * @method static void validDuration(mixed $value, string $message = '', string $exception = '')
 * @method static void validEntity(mixed $value, string $message = '', string $exception = '')
 * @method static void validEntities(mixed $value, string $message = '', string $exception = '')
 * @method static void validHexBinary(mixed $value, string $message = '', string $exception = '')
 * @method static void validID(mixed $value, string $message = '', string $exception = '')
 * @method static void validIDRef(mixed $value, string $message = '', string $exception = '')
 * @method static void validIDRefs(mixed $value, string $message = '', string $exception = '')
 * @method static void validInteger(mixed $value, string $message = '', string $exception = '')
 * @method static void validLang(mixed $value, string $message = '', string $exception = '')
 * @method static void validMonth(mixed $value, string $message = '', string $exception = '')
 * @method static void validName(mixed $value, string $message = '', string $exception = '')
 * @method static void validNCName(mixed $value, string $message = '', string $exception = '')
 * @method static void validNMToken(mixed $value, string $message = '', string $exception = '')
 * @method static void validNMTokens(mixed $value, string $message = '', string $exception = '')
 * @method static void validNegativeInteger(mixed $value, string $message = '', string $exception = '')
 * @method static void validNonNegativeInteger(mixed $value, string $message = '', string $exception = '')
 * @method static void validNonPositiveInteger(mixed $value, string $message = '', string $exception = '')
 * @method static void validNormalizedString(mixed $value, string $message = '', string $exception = '')
 * @method static void validQName(mixed $value, string $message = '', string $exception = '')
 * @method static void validString(mixed $value, string $message = '', string $exception = '')
 * @method static void validTime(mixed $value, string $message = '', string $exception = '')
 * @method static void validToken(mixed $value, string $message = '', string $exception = '')
 * @method static void validYearMonth(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidAllowedXPathFilter(mixed $value, array $allowed_axes, array $allowed_functions, string $message = '', string $exception = '')
 * @method static void nullOrValidAnyURI(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidBase64Binary(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidBoolean(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidDate(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidDateTime(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidDay(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidDecimal(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidDuration(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidEntity(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidEntities(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidHexBinary(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidID(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidIDRef(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidIDRefs(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidInteger(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidLang(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidMonth(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidName(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidNCName(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidNMToken(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidNMTokens(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidNegativeInteger(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidNonNegativeInteger(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidNonPositiveInteger(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidNormalizedString(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidQName(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidString(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidTime(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidToken(mixed $value, string $message = '', string $exception = '')
 * @method static void nullOrValidYearMonth(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidAllowedXPathFilter(mixed $value, array $allowed_axes, array $allowed_functions, string $message = '', string $exception = '')
 * @method static void allValidAnyURI(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidBase64Binary(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidBoolean(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidDate(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidDateTime(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidDay(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidDecimal(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidDuration(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidEntity(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidEntities(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidHexBinary(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidID(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidIDRef(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidIDRefs(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidInteger(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidLang(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidMonth(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidName(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidNCName(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidNMToken(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidNMTokens(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidNegativeInteger(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidNonNegativeInteger(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidNonPositiveInteger(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidNormalizedString(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidQName(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidString(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidTime(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidToken(mixed $value, string $message = '', string $exception = '')
 * @method static void allValidYearMonth(mixed $value, string $message = '', string $exception = '')
 */
class Assert extends BaseAssert
{
    use AnyURITrait;
    use Base64BinaryTrait;
    use BooleanTrait;
    use DateTrait;
    use DateTimeTrait;
    use DayTrait;
    use DecimalTrait;
    use DurationTrait;
    use HexBinaryTrait;
    use EntitiesTrait;
    use EntityTrait;
    use IDTrait;
    use IDRefTrait;
    use IDRefsTrait;
    use IntegerTrait;
    use LangTrait;
    use MonthTrait;
    use NameTrait;
    use NCNameTrait;
    use NMTokenTrait;
    use NMTokensTrait;
    use NegativeIntegerTrait;
    use NonNegativeIntegerTrait;
    use NonPositiveIntegerTrait;
    use NormalizedStringTrait;
    use QNameTrait;
    use StringTrait;
    use TimeTrait;
    use TokenTrait;
    use XPathFilterTrait;
    use YearMonthTrait;
}
