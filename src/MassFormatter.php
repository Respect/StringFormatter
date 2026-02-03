<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter;

use Respect\StringFormatter\Internal\UnitPromoter;

final readonly class MassFormatter implements Formatter
{
    use UnitPromoter;

    private const array UNIT_RATIOS = [
        't' => [1_000_000, 1],
        'kg' => [1_000, 1],
        'g' => [1, 1],
        'mg' => [1, 1_000],
    ];

    private const array UNIT_ALIASES = [];

    public function __construct(string $unit)
    {
        if (!isset(self::UNIT_RATIOS[$unit])) {
            throw new InvalidFormatterException('Unsupported metric mass unit');
        }

        $this->unit = $unit;
    }
}
