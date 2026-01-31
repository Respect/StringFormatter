<?php

declare(strict_types=1);

namespace Respect\StringFormatter;

use function implode;
use function in_array;
use function preg_replace;
use function preg_split;
use function sprintf;

use const PREG_SPLIT_NO_EMPTY;

final readonly class TrimFormatter implements Formatter
{
    private const string DEFAULT_MASK = " \t\n\r\0\x0B";

    private const string LEFT = 'left';
    private const string RIGHT = 'right';
    private const string BOTH = 'both';

    public function __construct(
        private string $mask = self::DEFAULT_MASK,
        private string $side = self::BOTH,
    ) {
        $this->validateSide();
    }

    public function format(string $input): string
    {
        if ($this->side === self::LEFT || $this->side === self::BOTH) {
            $input = $this->trimLeft($input);
        }

        if ($this->side === self::RIGHT || $this->side === self::BOTH) {
            $input = $this->trimRight($input);
        }

        return $input;
    }

    private function validateSide(): void
    {
        if (!in_array($this->side, [self::LEFT, self::RIGHT, self::BOTH], true)) {
            throw new InvalidFormatterException(
                sprintf(
                    'Invalid side "%s". Must be "left", "right", or "both".',
                    $this->side,
                ),
            );
        }
    }

    private function trimLeft(string $input): string
    {
        $regex = sprintf('/^[%s]++/u', $this->escapeRegex($this->mask));

        return preg_replace($regex, '', $input) ?? $input;
    }

    private function trimRight(string $input): string
    {
        $regex = sprintf('/[%s]++$/u', $this->escapeRegex($this->mask));

        return preg_replace($regex, '', $input) ?? $input;
    }

    private function escapeRegex(string $mask): string
    {
        $specialChars = '/\\^$.|?*+()[{';
        $chars = preg_split('//u', $mask, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $escaped = [];

        foreach ($chars as $char) {
            if (in_array($char, preg_split('//u', $specialChars, -1, PREG_SPLIT_NO_EMPTY) ?: [], true)) {
                $char = '\\' . $char;
            }

            $escaped[] = $char;
        }

        return implode('', $escaped);
    }
}
