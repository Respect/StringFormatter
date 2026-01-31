<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter\Test\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\StringFormatter\CreditCardFormatter;

#[CoversClass(CreditCardFormatter::class)]
final class CreditCardFormatterTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForVisaCards')]
    #[DataProvider('providerForMasterCard')]
    #[DataProvider('providerForAmexCards')]
    #[DataProvider('providerForDiscoverCards')]
    #[DataProvider('providerForJcbCards')]
    #[DataProvider('providerForDinersClubCards')]
    #[DataProvider('providerForUnionPayCards')]
    #[DataProvider('providerForRuPayCards')]
    #[DataProvider('providerForUnrecognizedCards')]
    #[DataProvider('providerForInputCleaning')]
    #[DataProvider('providerForEdgeCases')]
    public function itShouldFormatCreditCards(string $input, string $expected): void
    {
        $formatter = new CreditCardFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldHandleEmptyString(): void
    {
        $formatter = new CreditCardFormatter();

        $actual = $formatter->format('');

        self::assertSame('   ', $actual);
    }

    #[Test]
    #[DataProvider('providerForVisaDifferentLengths')]
    public function itShouldHandleVisaDifferentLengths(string $input, string $expected): void
    {
        $formatter = new CreditCardFormatter();

        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForVisaCards(): array
    {
        return [
            'visa 16 digits' => ['4123456789012345', '4123 4567 8901 2345'],
            'visa 16 digits with dashes' => ['4123-4567-8901-2345', '4123 4567 8901 2345'],
            'visa 16 digits with spaces' => ['4123 4567 8901 2345', '4123 4567 8901 2345'],
            'visa another' => ['4532015112830366', '4532 0151 1283 0366'],
            'visa starts with 4' => ['4916409457367128', '4916 4094 5736 7128'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForMasterCard(): array
    {
        return [
            'mastercard 51' => ['5112345678901234', '5112 3456 7890 1234'],
            'mastercard 55' => ['5512345678901234', '5512 3456 7890 1234'],
            'mastercard 52' => ['5212345678901234', '5212 3456 7890 1234'],
            'mastercard 53' => ['5312345678901234', '5312 3456 7890 1234'],
            'mastercard 54' => ['5412345678901234', '5412 3456 7890 1234'],
            'mastercard 2221' => ['2221123456789012', '2221 1234 5678 9012'],
            'mastercard 2720' => ['2720123456789012', '2720 1234 5678 9012'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForAmexCards(): array
    {
        return [
            'amex 34' => ['341234567890123', '3412 345678 90123'],
            'amex 37' => ['371234567890123', '3712 345678 90123'],
            'amex another 34' => ['347856241795641', '3478 562417 95641'],
            'amex another 37' => ['378282246310005', '3782 822463 10005'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForDiscoverCards(): array
    {
        return [
            'discover 6011' => ['6011000990139424', '6011 0009 9013 9424'],
            'discover 65' => ['6512345678901234', '6512 3456 7890 1234'],
            'discover 644' => ['6441234567890123', '6441 2345 6789 0123'],
            'discover 645' => ['6451234567890123', '6451 2345 6789 0123'],
            'discover 646' => ['6461234567890123', '6461 2345 6789 0123'],
            'discover 647' => ['6471234567890123', '6471 2345 6789 0123'],
            'discover 648' => ['6481234567890123', '6481 2345 6789 0123'],
            'discover 649' => ['6491234567890123', '6491 2345 6789 0123'],
            'discover 19 digits' => ['6011000990139424123', '6011 0009 9013 9424 123'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForJcbCards(): array
    {
        return [
            'jcb 3528' => ['3528000012345678', '3528 0000 1234 5678'],
            'jcb 3536' => ['3536000012345678', '3536 0000 1234 5678'],
            'jcb 3558' => ['3558000012345678', '3558 0000 1234 5678'],
            'jcb 3589' => ['3589000012345678', '3589 0000 1234 5678'],
            'jcb 19 digits' => ['3528000012345678901', '3528 0000 1234 5678 901'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForDinersClubCards(): array
    {
        return [
            'diners 300' => ['30012345678901', '3001 234567 8901'],
            'diners 301' => ['30112345678901', '3011 234567 8901'],
            'diners 305' => ['30512345678901', '3051 234567 8901'],
            'diners 309' => ['30912345678901', '3091 234567 8901'],
            'diners 36' => ['36123456789012', '3612 345678 9012'],
            'diners 38' => ['38123456789012', '3812 345678 9012'],
            'diners 16 digits (mastercard co-brand)' => ['3612345678901234', '3612 3456 7890 1234'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForUnionPayCards(): array
    {
        return [
            'unionpay 62 16 digits' => ['6212345678901234', '6212 3456 7890 1234'],
            'unionpay 62 19 digits' => ['6212345678901234567', '6212 3456 7890 1234 567'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForRuPayCards(): array
    {
        return [
            'rupay 60' => ['6012345678901234', '6012 3456 7890 1234'],
            'rupay 65' => ['6512345678901234', '6512 3456 7890 1234'],
            'rupay 81' => ['8112345678901234', '8112 3456 7890 1234'],
            'rupay 82' => ['8212345678901234', '8212 3456 7890 1234'],
            'rupay 508' => ['5081234567890123', '5081 2345 6789 0123'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForUnrecognizedCards(): array
    {
        return [
            'unknown 16 digit' => ['1234567890123456', '1234 5678 9012 3456'],
            'unknown starts with 1' => ['1111222233334444', '1111 2222 3333 4444'],
            'unknown starts with 2' => ['2111222233334444', '2111 2222 3333 4444'],
            'unknown starts with 3' => ['3111222233334444', '3111 2222 3333 4444'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForInputCleaning(): array
    {
        return [
            'with spaces' => ['4123 4567 8901 2345', '4123 4567 8901 2345'],
            'with dashes' => ['4123-4567-8901-2345', '4123 4567 8901 2345'],
            'with dots' => ['4123.4567.8901.2345', '4123 4567 8901 2345'],
            'mixed separators' => ['4123-4567.8901 2345', '4123 4567 8901 2345'],
            'with letters' => ['4123A4567B8901C2345', '4123 4567 8901 2345'],
            'with special chars' => ['4123!4567@8901#2345', '4123 4567 8901 2345'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForEdgeCases(): array
    {
        return [
            'empty string' => ['', '   '],
            'only spaces' => ['    ', '   '],
            'only dashes' => ['----', '   '],
            'only dots' => ['....', '   '],
            'only letters' => ['abcd', '   '],
            'short number' => ['123', '123   '],
            'mixed content' => ['abcd4123456789012345abcd', '4123 4567 8901 2345'],
            'numbers longer than pattern' => ['41234567890123456789', '4123 4567 8901 2345 678'],
        ];
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForVisaDifferentLengths(): array
    {
        return [
            'visa 13 digits' => ['4123456789012', '4123 4567 8901 2'],
            'visa 16 digits' => ['4123456789012345', '4123 4567 8901 2345'],
            'visa 19 digits' => ['4123456789012345678', '4123 4567 8901 2345 678'],
        ];
    }
}
