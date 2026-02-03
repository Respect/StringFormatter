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
use Respect\StringFormatter\ImperialMassFormatter;
use Respect\StringFormatter\InvalidFormatterException;

#[CoversClass(ImperialMassFormatter::class)]
final class ImperialMassFormatterTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForImperialMassPromotion')]
    public function itShouldPromoteImperialMass(string $unit, string $input, string $expected): void
    {
        $formatter = new ImperialMassFormatter($unit);
        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForImperialMassPromotion(): array
    {
        return [
            'ounces to pounds' => ['oz', '16', '1lb'],
            'pounds to stones' => ['lb', '14', '1st'],
            'pounds to long tons' => ['lb', '2240', '1ton'],
            'pounds to ounces (decimal input)' => ['lb', '0.5', '8oz'],
            'negative ounces to pounds' => ['oz', '-16', '-1lb'],
            'zero keeps base unit' => ['st', '0', '0st'],
        ];
    }

    #[Test]
    #[DataProvider('providerForNonNumericInput')]
    public function itShouldReturnInputUnchangedForNonNumericInput(string $input): void
    {
        $formatter = new ImperialMassFormatter('lb');
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

        new ImperialMassFormatter('kg');
    }
}
