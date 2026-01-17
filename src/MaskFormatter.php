<?php

declare(strict_types=1);

namespace Respect\StringFormatter;

use function array_merge;
use function array_pad;
use function array_unique;
use function count;
use function explode;
use function implode;
use function is_numeric;
use function mb_str_split;
use function mb_strlen;
use function mb_strpos;
use function mb_substr;
use function preg_match;
use function preg_split;
use function range;
use function sort;
use function sprintf;
use function str_contains;
use function str_starts_with;
use function substr;
use function trim;

final readonly class MaskFormatter implements Formatter
{
    public function __construct(
        private string $range,
        private string $replacement = '*',
    ) {
        if (!$this->isValidRange($range)) {
            throw new InvalidFormatterException(sprintf('"%s" is not a valid mask range', $range));
        }
    }

    public function format(string $input): string
    {
        $characters = mb_str_split($input);
        $inputLength = count($characters);

        foreach ($this->getPositionsToMask($input, $this->range) as $position) {
            if ($position < 0) {
                $actualPos = ($position * -1) - 1;
            } else {
                $actualPos = $position;
                if ($actualPos >= $inputLength) {
                    continue;
                }
            }

            $characters[$actualPos] = $this->replacement;
        }

        return implode('', $characters);
    }

    private function isValidRange(string $range): bool
    {
        $ranges = preg_split('/(?<!\\\\),/', $range) ?: [];
        foreach ($ranges as $range) {
            if (!$this->isValidSingleRange($range)) {
                return false;
            }
        }

        return true;
    }

    /** @return int[] */
    private function getPositionsToMask(string $input, string $range): array
    {
        $positions = [];
        $ranges = explode(',', $range);

        foreach ($ranges as $range) {
            $range = trim($range);
            if ($range === '') {
                continue;
            }

            $positions = array_merge($positions, $this->parseRange($input, $range));
        }

        sort($positions);

        return array_unique($positions);
    }

    /** @return int[] */
    private function parseRange(string $input, string $range): array
    {
        if (str_contains($range, '-')) {
            return $this->parseRangeWithDelimiter($input, $range);
        }

        $position = $this->parsePosition($input, $range);

        return $position !== null ? [$position] : [];
    }

    /** @return int[] */
    private function parseRangeWithDelimiter(string $input, string $range): array
    {
        // Handle special patterns: "N-" (from position N to end)
        if (preg_match('/^[^-]+(?<!\\\\)-$/', $range)) {
            $start = substr($range, 0, -1);
            $startPos = $this->parsePosition($input, $start);

            return range((int) $startPos, mb_strlen($input) - 1);
        }

        // Handle special pattern: "-N" (last N chars)
        if (str_starts_with($range, '-')) {
            $lastPosition = (int) mb_substr($range, 1);
            $inputLength = mb_strlen($input);

            return range($inputLength - $lastPosition, $inputLength - 1);
        }

        [$start, $end] = array_pad(preg_split('/(?<!\\\\)-/', $range, 2) ?: [], 2, '');

        $startPos = $this->parsePosition($input, $start);
        $endPos = $this->parsePosition($input, $end);

        if ($startPos === null || $endPos === null) {
            return [];
        }

        // For character delimiter ranges, the end position is negative
        if ($endPos < 0) {
            // Convert to actual position and exclude the delimiter itself
            $actualEndPos = ($endPos * -1) - 1;

            return range($startPos, $actualEndPos - 1);
        }

        // For numeric ranges, include both start and end positions
        return range($startPos, $endPos);
    }

    private function parsePosition(string $input, string $position): int|null
    {
        // Handle escaped characters
        if (str_starts_with($position, '\\')) {
            $actualChar = mb_substr($position, 1);
            $index = mb_strpos($input, $actualChar);

            return $index !== false ? (-$index - 1) : null;
        }

        // Handle numeric positions (1-based as per specification)
        if (is_numeric($position)) {
            $pos = (int) $position - 1; // Convert to 0-based for internal use

            return $pos >= 0 ? $pos : null;
        }

        // Handle character delimiters (any non-numeric character)
        $index = mb_strpos($input, $position);

        return $index !== false ? (-$index - 1) : null;
    }

    private function isValidSingleRange(string $range): bool
    {
        // Single numbers are valid ranges
        if (!$this->containsUnescapedHyphen($range)) {
            return $this->isValidPosition($range);
        }

        // Handle special patterns: "N-" (from position N to end) and "-N" (last N chars)
        if (preg_match('/^[^-]+(?<!\\\\)-$/', $range)) {
            $start = substr($range, 0, -1);

            return $this->isValidPosition($start);
        }

        if (str_starts_with($range, '-')) {
            $lastN = substr($range, 1);

            return is_numeric($lastN) && (int) $lastN > 0;
        }

        [$start, $end] = array_pad(preg_split('/(?<!\\\\)-/', $range, 2) ?: [], 2, '');

        if ($start === $end) {
            return false;
        }

        if (!$this->isValidPosition($start) || !$this->isValidPosition($end)) {
            return false;
        }

        if (is_numeric($start) && is_numeric($end)) {
            return (int) $start <= (int) $end;
        }

        return true;
    }

    private function isValidPosition(string $position): bool
    {
        if (is_numeric($position)) {
            return (int) $position > 0;
        }

        $length = mb_strlen($position);
        if (str_starts_with($position, '\\')) {
            return $length === 2;
        }

        if ($length !== 1) {
            return false;
        }

        return $position !== '-';
    }

    private function containsUnescapedHyphen(string $range): bool
    {
        // Find hyphens that are not preceded by a backslash
        $pattern = '/(?<!\\\\)-/';

        return preg_match($pattern, $range) === 1;
    }
}
