<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter\Mixin;

use Respect\StringFormatter\Formatter;
use Respect\StringFormatter\FormatterBuilder;

interface Chain extends Formatter
{
    public function mask(string $range, string $replacement = '*'): FormatterBuilder;

    public function pattern(string $pattern): FormatterBuilder;

    /** @param array<string, mixed> $parameters */
    public function placeholder(array $parameters): FormatterBuilder;
}
