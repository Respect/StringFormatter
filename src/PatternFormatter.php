<?php

declare(strict_types=1);

namespace Respect\StringFormatter;

use function array_key_exists;
use function count;
use function implode;
use function lcfirst;
use function mb_str_split;
use function mb_strlen;
use function mb_strtolower;
use function mb_strtoupper;
use function mb_substr;
use function preg_match;
use function sprintf;

final readonly class PatternFormatter implements Formatter
{
    private const array FILTERS = [
        '#' => '/^.$/u',
        '0' => '/^[0-9]$/',
        'A' => '/^[A-Z]$/',
        'a' => '/^[a-z]$/',
        'C' => '/^\p{L}$/u',
        'W' => '/^[\p{L}\p{N}]$/u',
    ];

    private const array TRANSFORMATIONS = [
        'd' => 'delete',
        'l' => 'lower',
        'L' => 'LOWER',
        'u' => 'upper',
        'U' => 'UPPER',
        'i' => 'invert',
        'I' => 'INVERT',
        'E' => 'reset',
    ];

    public function __construct(
        private string $pattern,
    ) {
        $this->validatePattern();
    }

    public function format(string $input): string
    {
        $chars = mb_str_split($input);
        $charIndex = 0;
        $output = [];
        $transform = null;
        $patternLength = mb_strlen($this->pattern);

        for ($i = 0; $i < $patternLength; $i++) {
            $char = mb_substr($this->pattern, $i, 1);

            // Handle escape sequences
            if ($char === '\\' && $i + 1 < $patternLength) {
                $next = mb_substr($this->pattern, $i + 1, 1);

                if (array_key_exists($next, self::TRANSFORMATIONS)) {
                    $type = self::TRANSFORMATIONS[$next];
                    if ($type === 'delete') {
                        $charIndex++;
                    } elseif ($type === 'reset') {
                        $transform = null;
                    } else {
                        $transform = $type;
                    }

                    $i++;
                    continue;
                }

                // Escaped literal character
                $output[] = $next;
                $i++;
                continue;
            }

            // Handle filter patterns
            if (array_key_exists($char, self::FILTERS)) {
                $repetition = $this->parseRepetition($i + 1);
                if ($repetition !== null) {
                    [, $max, $consumed] = $repetition;
                    $i += $consumed;
                } else {
                    $max = 1;
                }

                $count = 0;
                while (($max === null || $count < $max) && $charIndex < count($chars)) {
                    if (!$this->matches($char, $chars[$charIndex])) {
                        $charIndex++;
                        continue;
                    }

                    $output[] = $this->applyTransform($chars[$charIndex++], $transform);
                    $count++;

                    if ($transform === null || $transform !== lcfirst($transform)) {
                        continue;
                    }

                    $transform = null; // Clear single-use (lowercase) transformations
                }

                continue;
            }

            // Literal character
            $output[] = $char;
        }

        return implode('', $output);
    }

    private function validatePattern(): void
    {
        if ($this->pattern === '') {
            throw new InvalidFormatterException('Pattern cannot be empty');
        }

        $length = mb_strlen($this->pattern);

        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($this->pattern, $i, 1);

            // Check escape sequences
            if ($char === '\\') {
                if ($i + 1 >= $length) {
                    throw new InvalidFormatterException('Incomplete escape sequence at end of pattern');
                }

                $i++; // Skip the escaped character
                continue;
            }

            // Check for orphaned quantifiers (not after a filter)
            if ($char === '+' || $char === '*') {
                throw new InvalidFormatterException(
                    sprintf('Quantifier "%s" must follow a filter pattern at position %d', $char, $i),
                );
            }

            // Check for brace quantifiers
            if ($char === '{') {
                $remaining = mb_substr($this->pattern, $i);
                if (!$this->isValidBraceQuantifier($remaining)) {
                    throw new InvalidFormatterException(
                        sprintf('Invalid or malformed quantifier at position %d', $i),
                    );
                }
            }

            // If it's a filter, skip any following quantifier
            if (!array_key_exists($char, self::FILTERS)) {
                continue;
            }

            $repetition = $this->parseRepetition($i + 1);
            if ($repetition === null) {
                continue;
            }

            $i += $repetition[2];
        }
    }

    private function isValidBraceQuantifier(string $remaining): bool
    {
        // Matches exact count, range with min, or range with max only
        return preg_match('/^\{(\d+)\}/', $remaining) === 1
            || preg_match('/^\{(\d+),(\d*)\}/', $remaining) === 1
            || preg_match('/^\{,(\d+)\}/', $remaining) === 1;
    }

    /**
     * Parses a repetition quantifier (+, *, {n}, {n,}, {,m}, or {n,m}) starting at the given position.
     *
     * @return array{int, int|null, int}|null Returns [min, max, consumed chars] or null if no valid quantifier
     */
    private function parseRepetition(int $position): array|null
    {
        $remaining = mb_substr($this->pattern, $position);

        // Match + for one or more
        if (mb_substr($remaining, 0, 1) === '+') {
            return [1, null, 1];
        }

        // Match * for zero or more
        if (mb_substr($remaining, 0, 1) === '*') {
            return [0, null, 1];
        }

        // Match {n} for exact count
        if (preg_match('/^\{(\d+)\}/', $remaining, $matches) === 1) {
            $count = (int) $matches[1];

            return [$count, $count, mb_strlen($matches[0])];
        }

        // Match range quantifiers with minimum specified
        if (preg_match('/^\{(\d+),(\d*)\}/', $remaining, $matches) === 1) {
            $min = (int) $matches[1];
            $max = $matches[2] === '' ? null : (int) $matches[2];

            return [$min, $max, mb_strlen($matches[0])];
        }

        // Match range quantifiers with only maximum specified
        if (preg_match('/^\{,(\d+)\}/', $remaining, $matches) === 1) {
            return [0, (int) $matches[1], mb_strlen($matches[0])];
        }

        return null;
    }

    private function matches(string $filter, string $char): bool
    {
        return preg_match(self::FILTERS[$filter], $char) === 1;
    }

    private function applyTransform(string $char, string|null $transform): string
    {
        return match ($transform) {
            'lower', 'LOWER' => mb_strtolower($char),
            'upper', 'UPPER' => mb_strtoupper($char),
            'invert', 'INVERT' => mb_strtolower($char) === $char ? mb_strtoupper($char) : mb_strtolower($char),
            default => $char,
        };
    }
}
