<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter;

use function mb_strtolower;

final readonly class LowercaseFormatter implements Formatter
{
    public function format(string $input): string
    {
        return mb_strtolower($input);
    }
}
