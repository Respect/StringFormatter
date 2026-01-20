<?php

declare(strict_types=1);

namespace Respect\StringFormatter;

use Respect\StringFormatter\Modifiers\StringifyModifier;

use function array_key_exists;
use function is_string;
use function preg_replace_callback;

final readonly class PlaceholderFormatter implements Formatter
{
    /** @param array<string, mixed> $parameters */
    public function __construct(
        private array $parameters,
        private Modifier $modifier = new StringifyModifier(),
    ) {
    }

    public function format(string $input): string
    {
        return $this->formatUsingParameters($input, $this->parameters);
    }

    /** @param array<string, mixed> $parameters */
    public function formatUsing(string $input, array $parameters): string
    {
        return $this->formatUsingParameters($input, $this->parameters + $parameters);
    }

    /** @param array<string, mixed> $parameters */
    private function formatUsingParameters(string $input, array $parameters): string
    {
        return (string) preg_replace_callback(
            '/{{(\w+)(\|([^}]+))?}}/',
            fn(array $matches) => $this->processPlaceholder($matches, $parameters),
            $input,
        );
    }

    /**
     * @param array<int, string> $matches
     * @param array<string, mixed> $parameters
     */
    private function processPlaceholder(array $matches, array $parameters): string
    {
        $placeholder = $matches[0] ?? '';
        $name = $matches[1] ?? '';
        $pipe = $matches[3] ?? null;

        if (!array_key_exists($name, $parameters)) {
            return $placeholder;
        }

        $value = $parameters[$name];
        if (is_string($value) && $pipe === null) {
            return $value;
        }

        return $this->modifier->modify($value, $pipe);
    }
}
