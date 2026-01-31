<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter;

use function mb_strlen;
use function mb_substr;

final readonly class SecureCreditCardFormatter implements Formatter
{
    public function __construct(
        private string $maskChar = '*',
    ) {
    }

    public function format(string $input): string
    {
        $creditCardFormatter = new CreditCardFormatter();
        $cleaned = $creditCardFormatter->cleanInput($input);

        if (mb_strlen($cleaned) < 9) {
            return $cleaned;
        }

        $formatted = $creditCardFormatter->format($cleaned);
        $maskRange = $this->detectMaskRange($cleaned);

        return (new MaskFormatter($maskRange, $this->maskChar))->format($formatted);
    }

    private function detectMaskRange(string $cleaned): string
    {
        $length = mb_strlen($cleaned);
        $firstTwo = mb_substr($cleaned, 0, 2);

        // AMEX (4-6-5 format): mask middle group (positions 6-11)
        if ($firstTwo === '34' || $firstTwo === '37') {
            return '6-11';
        }

        // Diners Club 14-digit (4-6-4 format): mask middle group (positions 6-11)
        if ($length === 14) {
            $firstThree = mb_substr($cleaned, 0, 3);
            $prefix3 = (int) $firstThree;
            if (($prefix3 >= 300 && $prefix3 <= 305) || $prefix3 === 309 || $firstTwo === '36' || $firstTwo === '38') {
                return '6-11';
            }
        }

        // 19-digit cards (4-4-4-4-3 format): mask groups 2-4 (positions 6-9, 11-14, 16-19)
        if ($length > 16) {
            return '6-9,11-14,16-19';
        }

        // Default 16-digit (4-4-4-4 format): mask groups 2-3 (positions 6-9, 11-14)
        return '6-9,11-14';
    }
}
