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
use Respect\StringFormatter\MassFormatter;

#[CoversClass(MassFormatter::class)]
final class MassFormatterTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForMassPromotion')]
    public function itShouldPromoteMass(string $unit, string $input, string $expected): void
    {
        $formatter = new MassFormatter($unit);
        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForMassPromotion(): array
    {
        return [
            'grams to kilograms' => ['g', '1000', '1kg'],
            'grams to milligrams' => ['g', '0.001', '1mg'],
            'kilograms to tonnes' => ['kg', '1000', '1t'],
            'milligrams to grams' => ['mg', '1000', '1g'],
            'negative mass' => ['g', '-1000', '-1kg'],
            'zero keeps base unit' => ['g', '0', '0g'],
            'no rounding applied' => ['g', '1.23000', '1.23g'],
            'scientific notation supported' => ['g', '1e6', '1t'],
        ];
    }

    #[Test]
    #[DataProvider('providerForNonNumericInput')]
    public function itShouldReturnInputUnchangedForNonNumericInput(string $input): void
    {
        $formatter = new MassFormatter('g');
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
        $this->expectExceptionMessage('Unsupported metric mass unit');

        new MassFormatter('invalid');
    }
}
