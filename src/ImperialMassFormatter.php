<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter;

use Respect\StringFormatter\Internal\UnitPromoter;

final readonly class ImperialMassFormatter implements Formatter
{
    use UnitPromoter;

    private const array UNIT_RATIOS = [
        'ton' => [35_840, 1],
        'st' => [224, 1],
        'lb' => [16, 1],
        'oz' => [1, 1],
    ];

    private const array UNIT_ALIASES = [];

    public function __construct(string $unit)
    {
        if (!isset(self::UNIT_RATIOS[$unit])) {
            throw new InvalidFormatterException('Unsupported imperial mass unit');
        }

        $this->unit = $unit;
    }
}
