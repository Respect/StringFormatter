<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter;

use Respect\StringFormatter\Internal\UnitPromoter;

final readonly class TimeFormatter implements Formatter
{
    use UnitPromoter;

    private const array UNIT_ALIASES = [];

    private const array UNIT_RATIOS = [
        'mil' => [31_536_000_000, 1],
        'c' => [3_153_600_000, 1],
        'dec' => [315_360_000, 1],
        'y' => [31_536_000, 1],
        'mo' => [2_628_000, 1],
        'w' => [604_800, 1],
        'd' => [86_400, 1],
        'h' => [3_600, 1],
        'min' => [60, 1],
        's' => [1, 1],
        'ms' => [1, 1_000],
        'us' => [1, 1_000_000],
        'ns' => [1, 1_000_000_000],
    ];

    public function __construct(string $unit)
    {
        if (!isset(self::UNIT_RATIOS[$unit])) {
            throw new InvalidFormatterException('Unsupported time unit');
        }

        $this->unit = $unit;
    }
}
