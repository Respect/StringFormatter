<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter;

use Respect\StringFormatter\Internal\CompiledPattern;

use function implode;
use function preg_match;
use function preg_match_all;
use function preg_replace_callback;

final readonly class PatternFormatter implements Formatter
{
    private CompiledPattern $compiledPattern;

    public function __construct(private string $pattern)
    {
        $this->compiledPattern = CompiledPattern::compile($this->pattern);
    }

    public function format(string $input): string
    {
        $matches = [];
        preg_match($this->compiledPattern->search, $input, $matches);

        return preg_replace_callback('/%(\d+)\$/', function (array $m) use ($matches): string {
            $idx = (int) $m[1];
            if (!isset($matches[$idx]) || $matches[$idx] === '') {
                return '';
            }

            $instr = $this->compiledPattern->instructions[$idx];
            preg_match_all($instr['filter'], $matches[$idx], $subMatches);

            return CompiledPattern::transform(implode('', $subMatches[0]), $instr['transform']);
        }, $this->compiledPattern->replacement) ?? '';
    }
}
