<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter;

use ReflectionClass;
use Respect\StringFormatter\Mixin\Builder;

use function array_reduce;
use function ucfirst;

/** @mixin Builder */
final readonly class FormatterBuilder implements Formatter
{
    /** @var array<Formatter> */
    private array $formatters;

    public function __construct(Formatter ...$formatters)
    {
        $this->formatters = $formatters;
    }

    public static function create(Formatter ...$formatters): self
    {
        return new self(...$formatters);
    }

    public function format(string $input): string
    {
        if ($this->formatters === []) {
            throw new InvalidFormatterException('No formatters have been added to the builder');
        }

        return array_reduce(
            $this->formatters,
            static fn(string $carry, Formatter $formatter) => $formatter->format($carry),
            $input,
        );
    }

    /** @param array<int, mixed> $arguments */
    public function __call(string $name, array $arguments): self
    {
        /** @var class-string<Formatter> $class */
        $class = __NAMESPACE__ . '\\' . ucfirst($name) . 'Formatter';
        $reflection = new ReflectionClass($class);

        return clone($this, ['formatters' => [...$this->formatters, $reflection->newInstanceArgs($arguments)]]);
    }

    /** @param array<int, mixed> $arguments */
    public static function __callStatic(string $name, array $arguments): self
    {
        return self::create()->__call($name, $arguments);
    }
}
