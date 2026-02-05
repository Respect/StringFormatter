<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter\Modifiers;

use Respect\StringFormatter\Formatter;
use Respect\StringFormatter\FormatterBuilder;
use Respect\StringFormatter\Modifier;
use Throwable;

use function array_shift;
use function assert;
use function is_scalar;
use function is_string;
use function preg_split;

final readonly class FormatterModifier implements Modifier
{
    public function __construct(
        private Modifier $nextModifier,
    ) {
    }

    public function modify(mixed $value, string|null $pipe): string
    {
        if ($pipe === null) {
            return $this->nextModifier->modify($value, $pipe);
        }

        $arguments = preg_split('/(?<!\\\\):/', $pipe) ?: [];
        $name = array_shift($arguments);
        assert(is_string($name));

        $formatter = $this->tryToCreateFormatter($name, $arguments);

        if ($formatter === null || !is_scalar($value)) {
            return $this->nextModifier->modify($value, $pipe);
        }

        return $formatter->format((string) $value);
    }

    /** @param array<int, string> $arguments */
    private function tryToCreateFormatter(string $name, array $arguments): Formatter|null
    {
        try {
            return FormatterBuilder::__callStatic($name, $arguments);
        } catch (Throwable) {
            return null;
        }
    }
}
