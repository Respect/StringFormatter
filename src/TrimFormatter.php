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

final readonly class TrimFormatter implements Formatter
{
    /** @param 'both'|'left'|'right' $side */
    public function __construct(
        private string $side = 'both',
        private string|null $characters = null,
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
            'left' => mb_ltrim($input, $this->characters),
            'right' => mb_rtrim($input, $this->characters),
            default => mb_trim($input, $this->characters),
        };
    }
}
