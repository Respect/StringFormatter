<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter\Modifiers;

use ReflectionClass;
use ReflectionException;
use Respect\StringFormatter\Formatter;
use Respect\StringFormatter\Modifier;
use Throwable;

use function array_slice;
use function explode;
use function is_string;
use function ucfirst;

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

        // Try to parse as a formatter
        $parts = explode(':', $pipe);
        $formatterName = $parts[0];
        $arguments = array_slice($parts, 1);

        // Try to instantiate the formatter
        $formatter = $this->tryCreateFormatter($formatterName, $arguments);

        if ($formatter === null) {
            return $this->nextModifier->modify($value, $pipe);
        }

        // Convert value to string before passing to formatter
        if (!is_string($value)) {
            // Delegate to next modifier to convert to string first
            $stringValue = $this->nextModifier->modify($value, null);
        } else {
            $stringValue = $value;
        }

        try {
            return $formatter->format($stringValue);
        } catch (Throwable) {
            // If formatter fails, delegate to next modifier
            return $this->nextModifier->modify($value, $pipe);
        }
    }

    /** @param array<int, string> $arguments */
    private function tryCreateFormatter(string $name, array $arguments): Formatter|null
    {
        /** @var class-string<Formatter> $class */
        $class = 'Respect\\StringFormatter\\' . ucfirst($name) . 'Formatter';

        try {
            $reflection = new ReflectionClass($class);

            return $reflection->newInstanceArgs($arguments);
        } catch (ReflectionException) {
            return null;
        } catch (Throwable) {
            return null;
        }
    }
}
