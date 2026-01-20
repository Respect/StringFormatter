<?php

declare(strict_types=1);

namespace Respect\StringFormatter\Modifiers;

use Respect\StringFormatter\Modifier;

use function addcslashes;
use function is_bool;
use function is_scalar;
use function is_string;

final readonly class AutoQuoteModifier implements Modifier
{
    public function __construct(
        private Modifier $nextModifier,
    ) {
    }

    public function modify(mixed $value, string|null $pipe): string
    {
        if ($pipe === 'raw') {
            if (!is_scalar($value)) {
                return $this->nextModifier->modify($value, null);
            }

            return is_bool($value) ? (string) (int) $value : (string) $value;
        }

        if ($pipe === null && is_string($value)) {
            return '"' . addcslashes($value, '"') . '"';
        }

        return $this->nextModifier->modify($value, $pipe);
    }
}
