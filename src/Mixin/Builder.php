<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter\Mixin;

use Respect\StringFormatter\FormatterBuilder;

/** @mixin FormatterBuilder */
interface Builder
{
    public static function mask(string $range, string $replacement = '*'): Chain;

    public static function pattern(string $pattern): Chain;

    /** @param array<string, mixed> $parameters */
    public static function placeholder(array $parameters): Chain;
}
