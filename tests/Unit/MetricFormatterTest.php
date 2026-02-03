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
use Respect\StringFormatter\MetricFormatter;

#[CoversClass(MetricFormatter::class)]
final class MetricFormatterTest extends TestCase
{
    #[Test]
    #[DataProvider('providerForMetricLengthPromotion')]
    public function itShouldPromoteMetricLength(string $unit, string $input, string $expected): void
    {
        $formatter = new MetricFormatter($unit);
        $actual = $formatter->format($input);

        self::assertSame($expected, $actual);
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function providerForMetricLengthPromotion(): array
    {
        return [
            'example 1000m to km' => ['m', '1000', '1km'],
            'example 0.1m to cm' => ['m', '0.1', '10cm'],

            'meters to millimeters' => ['m', '0.001', '1mm'],
            'too small stays smallest' => ['m', '0.0009', '0.9mm'],
            'meters stays meters under 1000' => ['m', '999.999', '999.999m'],
            'negative meters to km' => ['m', '-1000', '-1km'],
            'zero keeps base unit' => ['m', '0', '0m'],

            'centimeters to meters' => ['cm', '100', '1m'],
            'millimeters to kilometers' => ['mm', '1000000', '1km'],

            'scientific notation supported' => ['m', '1e6', '1000km'],
            'no rounding applied' => ['m', '1.234500', '1.2345m'],
        ];
    }

    #[Test]
    #[DataProvider('providerForNonNumericInput')]
    public function itShouldReturnInputUnchangedForNonNumericInput(string $input): void
    {
        $formatter = new MetricFormatter('m');
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
        $this->expectExceptionMessage('Unsupported metric length unit');

        new MetricFormatter('invalid');
    }
}
