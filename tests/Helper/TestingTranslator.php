<?php

declare(strict_types=1);

namespace Respect\StringFormatter\Test\Helper;

use Symfony\Contracts\Translation\LocaleAwareInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Dumb test implementation of TranslatorInterface
 *
 * This implementation simply replaces input strings with mapped replacements
 * and doesn't implement any actual translation logic.
 * Used only to test that the TransModifier is using the translator correctly.
 */
final class TestingTranslator implements TranslatorInterface, LocaleAwareInterface
{
    /** @param array<string, string> $translations */
    public function __construct(
        private array $translations = [],
    ) {
    }

    /** @param array<string, mixed> $parameters */
    public function trans(
        string $id,
        array $parameters = [],
        string|null $domain = null,
        string|null $locale = null,
    ): string {
        return $this->translations[$id] ?? $id;
    }

    public function getLocale(): string
    {
        return 'en';
    }

    public function setLocale(string $locale): void
    {
        // Dummy implementation - not needed for testing
    }
}
