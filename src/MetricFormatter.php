<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter;

use Respect\StringFormatter\Internal\UnitPromoter;

final readonly class MetricFormatter implements Formatter
{
    use UnitPromoter;

    private const array UNIT_RATIOS = [
        'km' => [1_000, 1],
        'm' => [1, 1],
        'cm' => [1, 100],
        'mm' => [1, 1_000],
    ];

    private const array UNIT_ALIASES = [];

    public function __construct(string $unit)
    {
        if (!isset(self::UNIT_RATIOS[$unit])) {
            throw new InvalidFormatterException('Unsupported metric length unit');
        }

        $this->unit = $unit;
    }
}
