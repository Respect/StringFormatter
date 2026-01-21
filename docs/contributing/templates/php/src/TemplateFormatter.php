<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter;

final readonly class TemplateFormatter implements Formatter
{
    public function format(string $input): string
    {
        // Simple example: add "FORMATTED: " prefix to show it works
        return 'FORMATTED: ' . $input;
    }
}
