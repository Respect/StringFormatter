<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 * SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter\Mixin;

use Respect\StringFormatter\Formatter;
use Respect\StringFormatter\FormatterBuilder;

interface Chain extends Formatter
{
    public function area(string $unit): FormatterBuilder;

    public function date(string $format = 'Y-m-d H:i:s'): FormatterBuilder;

    public function imperialArea(string $unit): FormatterBuilder;

    public function imperialLength(string $unit): FormatterBuilder;

    public function imperialMass(string $unit): FormatterBuilder;

    public function mask(string $range, string $replacement = '*'): FormatterBuilder;

    public function metric(string $unit): FormatterBuilder;

    public function metricMass(string $unit): FormatterBuilder;

    public function number(
        int $decimals = 0,
        string $decimalSeparator = '.',
        string $thousandsSeparator = ',',
    ): FormatterBuilder;

    public function pattern(string $pattern): FormatterBuilder;

    /** @param array<string, mixed> $parameters */
    public function placeholder(array $parameters): FormatterBuilder;

    public function time(string $unit): FormatterBuilder;

    /** @param 'both'|'left'|'right' $side */
    public function trim(string $side = 'both', string $mask = " \t\n\r\0\x0B"): FormatterBuilder;
}
