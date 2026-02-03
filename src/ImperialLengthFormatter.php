<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter;

use Respect\StringFormatter\Internal\UnitPromoter;

final readonly class ImperialLengthFormatter implements Formatter
{
    use UnitPromoter;

    private const array UNIT_RATIOS = [
        'mi' => [63_360, 1],
        'yd' => [36, 1],
        'ft' => [12, 1],
        'in' => [1, 1],
    ];

    private const array UNIT_ALIASES = [];

    public function __construct(string $unit)
    {
        if (!isset(self::UNIT_RATIOS[$unit])) {
            throw new InvalidFormatterException('Unsupported imperial length unit');
        }

        $this->unit = $unit;
    }
}
