<?php

declare(strict_types=1);

namespace Respect\StringFormatter\Test\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\StringFormatter\SecretCreditCardFormatter;

#[CoversClass(SecretCreditCardFormatter::class)]
final class SecretCreditCardFormatterTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForVisaCards')]
    public function testShouldFormatAndMaskVisaCards(string $input, string $expected): void
    {
        $formatter = new SecretCreditCardFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForMasterCard')]
    public function testShouldFormatAndMaskMasterCard(string $input, string $expected): void
    {
        $formatter = new SecretCreditCardFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForAmexCards')]
    public function testShouldFormatAndMaskAmexCards(string $input, string $expected): void
    {
        $formatter = new SecretCreditCardFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForDiscoverCards')]
    public function testShouldFormatAndMaskDiscoverCards(string $input, string $expected): void
    {
        $formatter = new SecretCreditCardFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForJcbCards')]
    public function testShouldFormatAndMaskJcbCards(string $input, string $expected): void
    {
        $formatter = new SecretCreditCardFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForUnrecognizedCards')]
    public function testShouldFormatAndMaskUnrecognizedCardsWithDefault(string $input, string $expected): void
    {
        $formatter = new SecretCreditCardFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForCustomPattern')]
    public function testShouldUseCustomPattern(string $input, string $pattern, string $expected): void
    {
        $formatter = new SecretCreditCardFormatter($pattern);

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForCustomMaskRange')]
    public function testShouldUseCustomMaskRange(string $input, string $maskRange, string $expected): void
    {
        $formatter = new SecretCreditCardFormatter(null, $maskRange);

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForCustomMaskChar')]
    public function testShouldUseCustomMaskChar(string $input, string $maskChar, string $expected): void
    {
        $formatter = new SecretCreditCardFormatter(null, null, $maskChar);

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForInputCleaning')]
    public function testShouldCleanNonDigitCharacters(string $input, string $expected): void
    {
        $formatter = new SecretCreditCardFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('providerForEdgeCases')]
    public function testShouldHandleEdgeCases(string $input, string $expected): void
    {
        $formatter = new SecretCreditCardFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function testShouldHandleEmptyString(): void
    {
        $formatter = new SecretCreditCardFormatter();

        $actual = $formatter->format('');

        self::assertSame('', $actual);
    }

    #[Test]
    #[DataProvider('providerForAllOptions')]
    public function testShouldCombineAllCustomOptions(string $input, string $pattern, string $maskRange, string $maskChar, string $expected): void
    {
        $formatter = new SecretCreditCardFormatter($pattern, $maskRange, $maskChar);

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    public static function providerForVisaCards(): array
    {
        return [
            'visa 16 digits' => ['4123456789012345', '4123 **** **** 2345'],
            'visa with dashes' => ['4123-4567-8901-2345', '4123 **** **** 2345'],
            'visa with spaces' => ['4123 4567 8901 2345', '4123 **** **** 2345'],
            'visa another' => ['4532015112830366', '4532 **** **** 0366'],
            'visa starts with 4' => ['4916409457367128', '4916 **** **** 7128'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForMasterCard(): array
    {
        return [
            'mastercard 51' => ['5112345678901234', '5112 **** **** 1234'],
            'mastercard 55' => ['5512345678901234', '5512 **** **** 1234'],
            'mastercard 52' => ['5212345678901234', '5212 **** **** 1234'],
            'mastercard 53' => ['5312345678901234', '5312 **** **** 1234'],
            'mastercard 54' => ['5412345678901234', '5412 **** **** 1234'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForAmexCards(): array
    {
        return [
            'amex 34' => ['341234567890123', '3412 *******012 3'],
            'amex 37' => ['371234567890123', '3712 *******012 3'],
            'amex another 34' => ['347856241795641', '3478 *******956 1'],
            'amex another 37' => ['378282246310005', '3782 *******000 5'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForDiscoverCards(): array
    {
        return [
            'discover 6011' => ['6011000990139424', '6011 ******** 9424'],
            'discover 65' => ['6512345678901234', '6512 ******** 1234'],
            'discover 644' => ['6441234567890123', '6441 ******** 0123'],
            'discover 645' => ['6451234567890123', '6451 ******** 0123'],
            'discover 646' => ['6461234567890123', '6461 ******** 0123'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForJcbCards(): array
    {
        return [
            'jcb 3528' => ['3528000012345678', '3528 ******** 5678'],
            'jcb 3536' => ['3536000012345678', '3536 ******** 5678'],
            'jcb 3558' => ['3558000012345678', '3558 ******** 5678'],
            'jcb 3589' => ['3589000012345678', '3589 ******** 5678'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForUnrecognizedCards(): array
    {
        return [
            'unknown 16 digit' => ['1234567890123456', '1234 ************'],
            'unknown starts with 1' => ['1111222233334444', '1111 ************'],
            'unknown starts with 2' => ['2111222233334444', '2111 ************'],
        ];
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForCustomPattern(): array
    {
        return [
            'custom pattern without spaces' => ['4123456789012345', '################', '****************'],
            'custom pattern with dashes' => ['4123456789012345', '####-####-####-####', '****-****-****-2345'],
            'custom pattern groups of 3' => ['4123456789012345', '###-###-###-###-###', '412-***-***-***-234'],
        ];
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForCustomMaskRange(): array
    {
        return [
            'mask all except first 4' => ['4123456789012345', '5-', '4123 *************'],
            'mask last 4 only' => ['4123456789012345', '13-16', '4123 4567 8901 ****'],
            'mask middle 4' => ['4123456789012345', '6-9', '4123 **** 8901 2345'],
        ];
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForCustomMaskChar(): array
    {
        return [
            'mask with X' => ['4123456789012345', 'X', '4123 XXXXXXXX 2345'],
            'mask with #' => ['4123456789012345', '#', '4123 ######## 2345'],
            'mask with -' => ['4123456789012345', '-', '4123 -------- 2345'],
            'mask with •' => ['4123456789012345', '•', '4123 ••••••••••• 2345'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForInputCleaning(): array
    {
        return [
            'with spaces' => ['4123 4567 8901 2345', '4123 ******** 2345'],
            'with dashes' => ['4123-4567-8901-2345', '4123 ******** 2345'],
            'with dots' => ['4123.4567.8901.2345', '4123 ******** 2345'],
            'mixed separators' => ['4123-4567.8901 2345', '4123 ******** 2345'],
            'with letters' => ['4123A4567B8901C2345', '4123 ******** 2345'],
            'with special chars' => ['4123!4567@8901#2345', '4123 ******** 2345'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForEdgeCases(): array
    {
        return [
            'empty string' => ['', ''],
            'only spaces' => ['    ', ''],
            'only dashes' => ['----', ''],
            'only dots' => ['....', ''],
            'only letters' => ['abcd', ''],
            'short number' => ['123', '123'],
            'mixed content' => ['abcd4123456789012345abcd', '4123 ******** 2345'],
            'numbers longer than pattern' => ['41234567890123456789', '4123 ******** 678'],
        ];
    }

    /** @return array<string, array{0: string, 1: string, 2: string, 3: string, 4: string}> */
    public static function providerForAllOptions(): array
    {
        return [
            'all custom options' => [
                '4123456789012345',
                '####-####-####-####',
                '6-9',
                'X',
                '4123-XXXX-8901-2345',
            ],
            'different pattern and mask' => [
                '341234567890123',
                '#### ########## ######',
                '4-9',
                '#',
                '3412 ###### ## ##23',
            ],
        ];
    }
}
