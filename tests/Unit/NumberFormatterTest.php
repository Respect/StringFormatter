<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter\Test\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\StringFormatter\NumberFormatter;

#[CoversClass(NumberFormatter::class)]
final class NumberFormatterTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForBasicFormatting')]
    public function itShouldFormatBasicNumbers(
        int $decimals,
        string $decimalSeparator,
        string $thousandsSeparator,
        string $input,
        string $expected,
    ): void {
        $formatter = new NumberFormatter($decimals, $decimalSeparator, $thousandsSeparator);
        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: int, 1: string, 2: string, 3: string, 4: string}> */
    public static function providerForBasicFormatting(): array
    {
        return [
            'integer with default separators' => [
                0,
                '.',
                ',',
                '1234567',
                '1,234,567',
            ],
            'float with default separators' => [
                2,
                '.',
                ',',
                '1234567.89',
                '1,234,567.89',
            ],
            'small number with default' => [
                0,
                '.',
                ',',
                '123',
                '123',
            ],
            'zero with decimals' => [
                2,
                '.',
                ',',
                '0',
                '0.00',
            ],
            'negative number' => [
                0,
                '.',
                ',',
                '-1234567',
                '-1,234,567',
            ],
            'negative with decimals' => [
                2,
                '.',
                ',',
                '-1234.56',
                '-1,234.56',
            ],
            'european format' => [
                2,
                ',',
                '.',
                '1234567.89',
                '1.234.567,89',
            ],
            'no thousands separator' => [
                2,
                '.',
                '',
                '1234567.89',
                '1234567.89',
            ],
            'space thousands separator' => [
                2,
                ',',
                ' ',
                '1234567.89',
                '1 234 567,89',
            ],
            'three decimals' => [
                3,
                '.',
                ',',
                '1234.5678',
                '1,234.568',
            ],
            'no decimals input' => [
                2,
                '.',
                ',',
                '1000',
                '1,000.00',
            ],
            'string integer' => [
                0,
                '.',
                ',',
                '999',
                '999',
            ],
            'string float' => [
                1,
                '.',
                ',',
                '99.9',
                '99.9',
            ],
            'leading zeros stripped' => [
                0,
                '.',
                ',',
                '00123',
                '123',
            ],
            'scientific notation' => [
                0,
                '.',
                ',',
                '1e6',
                '1,000,000',
            ],
        ];
    }

    #[Test]
    #[DataProvider('providerForNonNumericInput')]
    public function itShouldReturnInputUnchangedForNonNumericInput(string $input): void
    {
        $formatter = new NumberFormatter();
        $actual = $formatter->format($input);

        self::assertSame($input, $actual);
    }

    /** @return array<string, array{0: string}> */
    public static function providerForNonNumericInput(): array
    {
        return [
            'empty string' => [''],
            'text' => ['abc'],
            'mixed text and numbers' => ['123abc'],
            'only comma' => [','],
            'only decimal' => ['.'],
            'multiple decimals' => ['1.2.3'],
            'letter e with non-numeric' => ['1e2e3'],
            'control character' => ["1\0234"],
            'special characters' => ['$1234'],
        ];
    }
}
