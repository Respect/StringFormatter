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
use Respect\StringFormatter\InvalidFormatterException;
use Respect\StringFormatter\TimeFormatter;

#[CoversClass(TimeFormatter::class)]
final class TimeFormatterTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForTimePromotion')]
    public function itShouldPromoteTime(string $unit, string $input, string $expected): void
    {
        $formatter = new TimeFormatter($unit);
        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForTimePromotion(): array
    {
        return [
            'seconds to minutes' => ['s', '60', '1min'],
            'seconds to hours' => ['s', '3600', '1h'],
            'seconds to days' => ['s', '86400', '1d'],
            'seconds to weeks' => ['s', '604800', '1w'],
            'seconds to months' => ['s', '2628000', '1mo'],
            'seconds to years' => ['s', '31536000', '1y'],
            'seconds to milliseconds' => ['s', '0.001', '1ms'],
            'seconds to microseconds (scientific notation)' => ['s', '1e-6', '1us'],
            'negative seconds to minutes' => ['s', '-60', '-1min'],
            'zero keeps base unit' => ['ms', '0', '0ms'],
        ];
    }

    #[Test]
    #[DataProvider('providerForNonNumericInput')]
    public function itShouldReturnInputUnchangedForNonNumericInput(string $input): void
    {
        $formatter = new TimeFormatter('s');
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
            'multiple decimals' => ['1.2.3'],
        ];
    }

    #[Test]
    public function itShouldThrowExceptionWhenUnitIsInvalid(): void
    {
        $this->expectException(InvalidFormatterException::class);

        new TimeFormatter('month');
    }
}
