<?php

declare(strict_types=1);

namespace Respect\StringFormatter\Modifiers;

use Respect\StringFormatter\BypassTranslator;
use Respect\StringFormatter\Modifier;
use Symfony\Contracts\Translation\TranslatorInterface;

use function array_map;
use function array_pop;
use function count;
use function implode;
use function in_array;
use function is_array;

final readonly class ListModifier implements Modifier
{
    private const array ALLOWED_PIPES = ['list', 'list:and', 'list:or'];

    public function __construct(
        private Modifier $nextModifier,
        private TranslatorInterface $translator = new BypassTranslator(),
    ) {
    }

    public function modify(mixed $value, string|null $pipe): string
    {
        if (!$pipe || !in_array($pipe, self::ALLOWED_PIPES) || !is_array($value)) {
            return $this->nextModifier->modify($value, $pipe);
        }

        if ($value === []) {
            return $this->nextModifier->modify($value, $pipe);
        }

        $modifiedValues = array_map(fn($item) => $this->nextModifier->modify($item, null), $value);

        $conjunction = $this->translator->trans(match ($pipe) {
            'list:and', 'list' => 'and',
            'list:or' => 'or',
        });

        if (count($value) < 3) {
            return implode(' ' . $conjunction . ' ', $modifiedValues);
        }

        $last = array_pop($modifiedValues);

        return implode(', ', $modifiedValues) . ', ' . $conjunction . ' ' . $last;
    }
}
