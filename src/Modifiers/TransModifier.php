<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter\Modifiers;

use Respect\StringFormatter\BypassTranslator;
use Respect\StringFormatter\Modifier;
use Symfony\Contracts\Translation\TranslatorInterface;

use function is_string;

final readonly class TransModifier implements Modifier
{
    public function __construct(
        private Modifier $nextModifier,
        private TranslatorInterface $translator = new BypassTranslator(),
    ) {
    }

    public function modify(mixed $value, string|null $pipe): string
    {
        if ($pipe !== 'trans') {
            return $this->nextModifier->modify($value, $pipe);
        }

        if (!is_string($value)) {
            return $this->nextModifier->modify($value, null);
        }

        return $this->translator->trans($value);
    }
}
