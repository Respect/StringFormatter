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
use Respect\StringFormatter\ImperialLengthFormatter;
use Respect\StringFormatter\InvalidFormatterException;

#[CoversClass(ImperialLengthFormatter::class)]
final class ImperialLengthFormatterTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForImperialLengthPromotion')]
    public function itShouldPromoteImperialLength(string $unit, string $input, string $expected): void
    {
        $formatter = new ImperialLengthFormatter($unit);
        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForImperialLengthPromotion(): array
    {
        return [
            'inches to feet' => ['in', '12', '1ft'],
            'inches to yards' => ['in', '36', '1yd'],
            'inches to miles' => ['in', '63360', '1mi'],
            'feet to miles' => ['ft', '5280', '1mi'],
            'negative inches to feet' => ['in', '-12', '-1ft'],
            'zero keeps base unit' => ['yd', '0', '0yd'],
        ];
    }

    #[Test]
    #[DataProvider('providerForNonNumericInput')]
    public function itShouldReturnInputUnchangedForNonNumericInput(string $input): void
    {
        $formatter = new ImperialLengthFormatter('in');
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

        new ImperialLengthFormatter('cm');
    }
}
