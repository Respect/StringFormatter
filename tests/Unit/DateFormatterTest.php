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
use Respect\StringFormatter\DateFormatter;

#[CoversClass(DateFormatter::class)]
final class DateFormatterTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForBasicFormatting')]
    public function itShouldFormatBasicDates(
        string $format,
        string $input,
        string $expected,
    ): void {
        $formatter = new DateFormatter($format);
        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForBasicFormatting(): array
    {
        return [
            'default format with datetime string' => [
                'Y-m-d H:i:s',
                '2024-01-15 10:30:45',
                '2024-01-15 10:30:45',
            ],
            'date only format' => [
                'Y-m-d',
                '2024-01-15 10:30:45',
                '2024-01-15',
            ],
            'time only format' => [
                'H:i:s',
                '2024-01-15 10:30:45',
                '10:30:45',
            ],
            'iso 8601 format' => [
                'c',
                '2024-01-15T10:30:45+00:00',
                '2024-01-15T10:30:45+00:00',
            ],
            'american format' => [
                'm/d/Y',
                '2024-01-15',
                '01/15/2024',
            ],
            'european format' => [
                'd.m.Y',
                '2024-01-15',
                '15.01.2024',
            ],
            'month name format' => [
                'l, F j, Y',
                '2024-01-15 10:30:45',
                'Monday, January 15, 2024',
            ],
            'short month format' => [
                'M d, Y',
                '2024-01-15',
                'Jan 15, 2024',
            ],
            'abbreviated weekday' => [
                'D',
                '2024-01-15',
                'Mon',
            ],
            'unix timestamp' => [
                'U',
                '2024-01-15T00:00:00Z',
                '1705276800',
            ],
            'with time zone' => [
                'Y-m-d H:i:s T',
                '2024-01-15T10:30:45Z',
                '2024-01-15 10:30:45 Z',
            ],
            'ordinal date format' => [
                'Y-z',
                '2024-01-15',
                '2024-14',
            ],
            'week number' => [
                'Y-W',
                '2024-01-15',
                '2024-03',
            ],
            'day of week numeric' => [
                'N',
                '2024-01-15',
                '1',
            ],
            'parse date string without time' => [
                'Y-m-d',
                '2024-01-15',
                '2024-01-15',
            ],
            'parse string with mixed format' => [
                'd-m-Y',
                '15-01-2024',
                '15-01-2024',
            ],
        ];
    }

    #[Test]
    #[DataProvider('providerForUnparsableInput')]
    public function itShouldReturnInputUnchangedForUnparsableInput(string $input): void
    {
        $formatter = new DateFormatter();
        $actual = $formatter->format($input);

        self::assertSame($input, $actual);
    }

    /** @return array<string, array{0: string}> */
    public static function providerForUnparsableInput(): array
    {
        return [
            'invalid date' => ['2024-13-45'],
            'random text with invalid chars' => ['@#$%^&*()'],
            'invalid format numeric only' => ['9999999999999999999'],
        ];
    }

    #[Test]
    #[DataProvider('providerForInvalidDateStrings')]
    public function itShouldReturnInputUnchangedForInvalidDateStrings(string $input): void
    {
        $formatter = new DateFormatter('Y-m-d');
        $actual = $formatter->format($input);

        self::assertSame($input, $actual);
    }

    /** @return array<string, array{0: string}> */
    public static function providerForInvalidDateStrings(): array
    {
        return [
            // These throw DateMalformedStringException in PHP 8.3+
            'completely invalid format' => ['not-a-date-at-all'],
            'text with date' => ['hello 2024-01-15'],
            'date at end after text' => ['text then 2024-01-15'],
            'random symbols' => ['@#$%^&*()'],
            'only symbols' => ['------'],
        ];
    }

    #[Test]
    #[DataProvider('providerForValidDateStrings')]
    public function itShouldFormatValidDateStringsWithoutErrors(string $input, string $expectedFormatted): void
    {
        $formatter = new DateFormatter('Y-m-d');

        // Verify that when DateTime can construct with no errors, formatting succeeds
        $actual = $formatter->format($input);

        self::assertSame($expectedFormatted, $actual);
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function providerForValidDateStrings(): array
    {
        return [
            'valid iso date' => ['2024-01-15', '2024-01-15'],
            'valid with time' => ['2024-01-15 10:30:45', '2024-01-15'],
            'valid with timezone' => ['2024-01-15T10:30:45Z', '2024-01-15'],
            'valid relative format' => ['January 15, 2024', '2024-01-15'],
            'valid slash format' => ['01/15/2024', '2024-01-15'],
            'valid dot format' => ['15.01.2024', '2024-01-15'],
        ];
    }

    #[Test]
    public function itShouldCheckDateTimeLastErrorsForStrictValidation(): void
    {
        // This test documents that the formatter uses DateTime::getLastErrors()
        // to perform strict validation beyond exception handling

        $formatter = new DateFormatter('Y-m-d');

        // Valid dates should format successfully
        self::assertSame('2024-01-15', $formatter->format('2024-01-15'));
        self::assertSame('2024-01-15', $formatter->format('2024-01-15 10:30:45'));

        // Invalid dates should return input unchanged
        self::assertSame('not a date', $formatter->format('not a date'));
        self::assertSame('2024-02-30', $formatter->format('2024-02-30'));
    }
}
