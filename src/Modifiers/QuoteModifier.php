<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter\Modifiers;

use Respect\StringFormatter\Modifier;

use function addcslashes;
use function is_scalar;
use function sprintf;

final readonly class QuoteModifier implements Modifier
{
    public function __construct(
        private Modifier $nextModifier,
        private string $quote = '`',
    ) {
    }

    public function modify(mixed $value, string|null $pipe): string
    {
        if ($pipe !== 'quote') {
            return $this->nextModifier->modify($value, $pipe);
        }

        if (!is_scalar($value)) {
            return $this->nextModifier->modify($value, null);
        }

        return sprintf('%s%s%s', $this->quote, addcslashes((string) $value, $this->quote), $this->quote);
    }
}
