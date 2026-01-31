<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter;

use function is_numeric;
use function number_format;

final readonly class NumberFormatter implements Formatter
{
    public function __construct(
        private int $decimals = 0,
        private string $decimalSeparator = '.',
        private string $thousandsSeparator = ',',
    ) {
    }

    public function format(string $input): string
    {
        if (!is_numeric($input)) {
            return $input;
        }

        return number_format(
            (float) $input,
            $this->decimals,
            $this->decimalSeparator,
            $this->thousandsSeparator,
        );
    }
}
