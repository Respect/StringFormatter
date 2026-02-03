<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter;

use Respect\StringFormatter\Internal\UnitPromoter;

final readonly class ImperialAreaFormatter implements Formatter
{
    use UnitPromoter;

    private const array UNIT_RATIOS = [
        'mi^2' => [4_014_489_600, 1],
        'ac' => [6_272_640, 1],
        'yd^2' => [1_296, 1],
        'ft^2' => [144, 1],
        'in^2' => [1, 1],
    ];

    private const array UNIT_ALIASES = [
        'mi^2' => 'mi²',
        'ac' => 'ac',
        'yd^2' => 'yd²',
        'ft^2' => 'ft²',
        'in^2' => 'in²',
    ];

    public function __construct(string $unit)
    {
        if (!isset(self::UNIT_RATIOS[$unit])) {
            throw new InvalidFormatterException('Unsupported imperial area unit');
        }

        $this->unit = $unit;
    }
}
