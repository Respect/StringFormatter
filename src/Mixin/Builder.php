<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 * SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter\Mixin;

use Respect\StringFormatter\FormatterBuilder;

/** @mixin FormatterBuilder */
interface Builder
{
    public static function area(string $unit): FormatterBuilder;

    public static function creditCard(): FormatterBuilder;

    public static function secureCreditCard(string $maskChar = '*'): FormatterBuilder;

    public static function imperialArea(string $unit): FormatterBuilder;

    public static function imperialLength(string $unit): FormatterBuilder;

    public static function imperialMass(string $unit): FormatterBuilder;

    public static function date(string $format = 'Y-m-d H:i:s'): FormatterBuilder;

    public static function lowercase(): FormatterBuilder;

    public static function mask(string $range, string $replacement = '*'): FormatterBuilder;

    public static function metric(string $unit): FormatterBuilder;

    public static function number(
        int $decimals = 0,
        string $decimalSeparator = '.',
        string $thousandsSeparator = ',',
    ): FormatterBuilder;

    public static function metricMass(string $unit): FormatterBuilder;

    public static function pattern(string $pattern): FormatterBuilder;

    /** @param array<string, mixed> $parameters */
    public static function placeholder(array $parameters): FormatterBuilder;

    public static function time(string $unit): FormatterBuilder;

    public static function uppercase(): FormatterBuilder;
}
