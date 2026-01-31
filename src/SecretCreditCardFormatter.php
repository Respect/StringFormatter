<?php

declare(strict_types=1);

namespace Respect\StringFormatter;

use function mb_strlen;

final readonly class SecretCreditCardFormatter implements Formatter
{
    public function __construct(
        private string|null $pattern = null,
        private string|null $maskRange = null,
        private string $maskChar = '*',
    ) {
    }

    public function format(string $input): string
    {
        $creditCardFormatter = new CreditCardFormatter($this->pattern);
        $cleaned = $creditCardFormatter->cleanInput($input);

        $formatted = $creditCardFormatter->format($cleaned);
        $maskRange = $this->maskRange ?? $this->detectMaskRange($cleaned);

        return (new MaskFormatter($maskRange, $this->maskChar))->format($formatted);
    }

    private function detectMaskRange(string $cleaned): string
    {
        $length = mb_strlen($cleaned);

        if ($length === 15) {
            return '6-12';
        }

        return '6-9,11-14';
    }
}
