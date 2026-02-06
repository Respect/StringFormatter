<?php

declare(strict_types=1);

namespace Respect\StringFormatter;

use function mb_substr;
use function preg_replace;

final readonly class CreditCardFormatter implements Formatter
{
    private const array CARD_PATTERNS = [
        'amex' => '#### ########## ######',
        'visa' => '#### #### #### ####',
        'mastercard' => '#### #### #### ####',
        'discover' => '#### #### #### ####',
        'jcb' => '#### #### #### ####',
        'default' => '#### #### #### ####',
    ];

    public function __construct(
        private string|null $pattern = null,
    ) {
    }

    public function format(string $input): string
    {
        $cleaned = $this->cleanInput($input);
        $pattern = $this->pattern ?? $this->detectPattern($cleaned);

        $formatter = new PatternFormatter($pattern);

        return $formatter->format($cleaned);
    }

    public function cleanInput(string $input): string
    {
        return preg_replace('/[^0-9]/', '', $input) ?? '';
    }

    public function detectPattern(string $input): string
    {
        if ($input === '') {
            return self::CARD_PATTERNS['default'];
        }

        return $this->getPatternForCardType($this->detectCardType($input));
    }

    public function getPatternForCardType(string|null $cardType): string
    {
        return self::CARD_PATTERNS[$cardType] ?? self::CARD_PATTERNS['default'];
    }

    private function detectCardType(string|null $input): string|null
    {
        if ($input === '' || $input === null) {
            return null;
        }

        $firstTwo = mb_substr($input, 0, 2);

        if ($firstTwo === '34' || $firstTwo === '37') {
            return 'amex';
        }

        if ($firstTwo === '35') {
            return 'jcb';
        }

        $first = mb_substr($input, 0, 1);

        return match ($first) {
            '4' => 'visa',
            '5' => 'mastercard',
            '6' => 'discover',
            default => null,
        };
    }
}
