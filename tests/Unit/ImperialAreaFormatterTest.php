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
use Respect\StringFormatter\ImperialAreaFormatter;
use Respect\StringFormatter\InvalidFormatterException;

#[CoversClass(ImperialAreaFormatter::class)]
final class ImperialAreaFormatterTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForImperialAreaPromotion')]
    public function itShouldPromoteImperialArea(string $unit, string $input, string $expected): void
    {
        $formatter = new ImperialAreaFormatter($unit);
        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForImperialAreaPromotion(): array
    {
        return [
            'square inches to square feet' => ['in^2', '144', '1ft²'],
            'square feet to acres' => ['ft^2', '43560', '1ac'],
            'acres to square miles' => ['ac', '640', '1mi²'],
            'negative square feet to acres' => ['ft^2', '-43560', '-1ac'],
            'zero keeps base unit' => ['yd^2', '0', '0yd²'],
        ];
    }

    #[Test]
    #[DataProvider('providerForNonNumericInput')]
    public function itShouldReturnInputUnchangedForNonNumericInput(string $input): void
    {
        $formatter = new ImperialAreaFormatter('ft^2');
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

        new ImperialAreaFormatter('m2');
    }
}
