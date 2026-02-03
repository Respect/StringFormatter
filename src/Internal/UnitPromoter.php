<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter\Internal;

use function abs;
use function array_key_first;
use function array_key_last;
use function array_keys;
use function is_numeric;

trait UnitPromoter
{
    private readonly string $unit;

    public function format(string $input): string
    {
        return self::promote(
            input: $input,
            inputUnit: $this->unit,
            ratiosToBase: self::UNIT_RATIOS,
            unitAliases: self::UNIT_ALIASES,
            orderedUnits: array_keys(self::UNIT_RATIOS),
            smallestUnit: array_key_last(self::UNIT_RATIOS),
            largestUnit: array_key_first(self::UNIT_RATIOS),
        );
    }

    /**
     * @param array<string, array{0: int, 1: int}> $ratiosToBase
     * @param array<string, string> $unitAliases
     * @param list<string> $orderedUnits
     */
    private static function promote(
        string $input,
        string $inputUnit,
        array $ratiosToBase,
        array $orderedUnits,
        array $unitAliases,
        string $smallestUnit,
        string $largestUnit,
    ): string {
        if (!is_numeric($input)) {
            return $input;
        }

        $amount = (float) $input;
        if ($amount == 0) {
            return '0' . ($unitAliases[$inputUnit] ?? $inputUnit);
        }

        [$baseNumerator, $baseDenominator] = $ratiosToBase[$inputUnit];
        $baseValue = $amount * $baseNumerator / $baseDenominator;

        $bestUnit = null;
        $bestValue = null;

        foreach ($orderedUnits as $unit) {
            [$unitNumerator, $unitDenominator] = $ratiosToBase[$unit];
            $candidateValue = $baseValue * $unitDenominator / $unitNumerator;
            $absCandidateValue = abs($candidateValue);

            if ($absCandidateValue >= 1 && $absCandidateValue < 1000) {
                $bestUnit = $unit;
                $bestValue = $candidateValue;
                break;
            }
        }

        if ($bestUnit === null) {
            [$largestNumerator, $largestDenominator] = $ratiosToBase[$largestUnit];
            $largestValue = $baseValue * $largestDenominator / $largestNumerator;
            if (abs($largestValue) >= 1) {
                $bestUnit = $largestUnit;
                $bestValue = $largestValue;
            } else {
                [$smallestNumerator, $smallestDenominator] = $ratiosToBase[$smallestUnit];
                $smallestValue = $baseValue * $smallestDenominator / $smallestNumerator;
                $bestUnit = $smallestUnit;
                $bestValue = $smallestValue;
            }
        }

        return (string) $bestValue . ($unitAliases[$bestUnit] ?? $bestUnit);
    }
}
