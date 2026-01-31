<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter;

use function in_array;
use function mb_ltrim;
use function mb_rtrim;
use function mb_trim;
use function sprintf;

/**
 * Trims characters from strings using multibyte-safe functions.
 *
 * When no mask is provided, trims all Unicode whitespace characters including:
 * regular space, tab, newline, carriage return, vertical tab, form feed,
 * no-break space (U+00A0), em space (U+2003), ideographic space (U+3000), and others.
 *
 * @see https://www.php.net/manual/en/function.mb-trim.php
 */
final readonly class TrimFormatter implements Formatter
{
    /**
     * @param 'both'|'left'|'right' $side Which side(s) to trim
     * @param string|null $mask Characters to trim, or null for default Unicode whitespace
     */
    public function __construct(
        private string $side = 'both',
        private string|null $mask = null,
    ) {
        if (!in_array($this->side, ['left', 'right', 'both'], true)) {
            throw new InvalidFormatterException(
                sprintf('Invalid side "%s". Must be "left", "right", or "both".', $this->side),
            );
        }
    }

    public function format(string $input): string
    {
        return match ($this->side) {
            'left' => mb_ltrim($input, $this->mask),
            'right' => mb_rtrim($input, $this->mask),
            default => mb_trim($input, $this->mask),
        };
    }
}
