<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter\Modifiers;

use Respect\StringFormatter\Modifier;

use function is_string;

final readonly class StringPassthroughModifier implements Modifier
{
    public function __construct(
        private Modifier $nextModifier,
    ) {
    }

    public function modify(mixed $value, string|null $pipe): string
    {
        if (is_string($value)) {
            return $value;
        }

        return $this->nextModifier->modify($value, $pipe);
    }
}
