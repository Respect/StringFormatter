<?php

declare(strict_types=1);

namespace Respect\StringFormatter;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Bypass translator that always returns the original input.
 *
 * This implementation works regardless of whether symfony/translation
 * is installed, providing a fallback.
 */
final class BypassTranslator implements TranslatorInterface
{
    /** @param array<string, mixed> $parameters */
    public function trans(
        string $id,
        array $parameters = [],
        string|null $domain = null,
        string|null $locale = null,
    ): string {
        return $id;
    }

    public function getLocale(): string
    {
        return 'en';
    }
}
