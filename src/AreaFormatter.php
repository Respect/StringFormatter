<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter;

use Respect\StringFormatter\Internal\UnitPromoter;

final readonly class AreaFormatter implements Formatter
{
    use UnitPromoter;

    private const array UNIT_RATIOS = [
        'km^2' => [1_000_000, 1],
        'ha' => [10_000, 1],
        'a' => [100, 1],
        'm^2' => [1, 1],
        'cm^2' => [1, 10_000],
        'mm^2' => [1, 1_000_000],
    ];

    private const array UNIT_ALIASES = [
        'km^2' => 'km²',
        'ha' => 'ha',
        'a' => 'a',
        'm^2' => 'm²',
        'cm^2' => 'cm²',
        'mm^2' => 'mm²',
    ];

    public function __construct(string $unit)
    {
        if (!isset(self::UNIT_RATIOS[$unit])) {
            throw new InvalidFormatterException('Unsupported area unit');
        }

        $this->unit = $unit;
    }
}
