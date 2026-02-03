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
use Respect\StringFormatter\AreaFormatter;
use Respect\StringFormatter\InvalidFormatterException;

#[CoversClass(AreaFormatter::class)]
final class AreaFormatterTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForAreaPromotion')]
    public function itShouldPromoteArea(string $unit, string $input, string $expected): void
    {
        $formatter = new AreaFormatter($unit);
        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForAreaPromotion(): array
    {
        return [
            'square meters to ares' => ['m^2', '100', '1a'],
            'ares to hectares' => ['a', '100', '1ha'],
            'hectares to square kilometers' => ['ha', '100', '1km²'],
            'square meters to square centimeters' => ['m^2', '0.0001', '1cm²'],
            'square millimeters to square centimeters' => ['mm^2', '100', '1cm²'],
            'negative hectares to square kilometers' => ['ha', '-100', '-1km²'],
            'zero keeps base unit' => ['m^2', '0', '0m²'],
        ];
    }

    #[Test]
    #[DataProvider('providerForNonNumericInput')]
    public function itShouldReturnInputUnchangedForNonNumericInput(string $input): void
    {
        $formatter = new AreaFormatter('m^2');
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

        new AreaFormatter('invalid');
    }
}
