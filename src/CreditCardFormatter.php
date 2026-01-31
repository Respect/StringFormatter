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
use function preg_replace;

final readonly class CreditCardFormatter implements Formatter
{
    private const string DEFAULT_16 = '#### #### #### ####';
    private const string DEFAULT_19 = '#### #### #### #### ###';
    private const string AMEX = '#### ###### #####';
    private const string DINERS_14 = '#### ###### ####';

    public function format(string $input): string
    {
        $cleaned = $this->cleanInput($input);
        $pattern = $this->detectPattern($cleaned);

        $formatter = new PatternFormatter($pattern);

        return $formatter->format($cleaned);
    }

    public function cleanInput(string $input): string
    {
        return preg_replace('/[^0-9]/', '', $input) ?? '';
    }

    public function detectPattern(string $input): string
    {
        $length = mb_strlen($input);
        $firstTwo = mb_substr($input, 0, 2);
        $firstThree = mb_substr($input, 0, 3);

        // American Express: starts with 34 or 37 (15 digits, 4-6-5 format)
        if ($firstTwo === '34' || $firstTwo === '37') {
            return self::AMEX;
        }

        // Diners Club International: 14 digits, starts with 300-305, 309, 36, 38
        if ($length === 14) {
            $prefix3 = (int) $firstThree;
            if (($prefix3 >= 300 && $prefix3 <= 305) || $prefix3 === 309 || $firstTwo === '36' || $firstTwo === '38') {
                return self::DINERS_14;
            }
        }

        // 19-digit cards (some Visa, Discover, JCB, UnionPay)
        if ($length > 16) {
            return self::DEFAULT_19;
        }

        // Default 4-4-4-4: Visa, Mastercard, Discover, JCB, UnionPay, RuPay, etc.
        return self::DEFAULT_16;
    }
}
