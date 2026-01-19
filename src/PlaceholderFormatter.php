<?php

declare(strict_types=1);

namespace Respect\StringFormatter;

use Respect\Stringifier\HandlerStringifier;
use Respect\Stringifier\Stringifier;

use function array_key_exists;
use function is_string;
use function preg_replace_callback;

final readonly class PlaceholderFormatter implements Formatter
{
    private Stringifier $stringifier;

    /** @param array<string, mixed> $parameters */
    public function __construct(
        private array $parameters,
        Stringifier|null $stringifier = null,
    ) {
        $this->stringifier = $stringifier ?? HandlerStringifier::create();
    }

    public function format(string $input): string
    {
        return $this->formatWithParameters($input, $this->parameters);
    }

    /** @param array<string, mixed> $parameters */
    public function formatWith(string $input, array $parameters): string
    {
        return $this->formatWithParameters($input, $this->parameters + $parameters);
    }

    /** @param array<string, mixed> $parameters */
    private function formatWithParameters(string $input, array $parameters): string
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
        [$placeholder, $name] = $matches;

        if (!array_key_exists($name, $parameters)) {
            return $placeholder;
        }

        $value = $parameters[$name];
        if (is_string($value)) {
            return $value;
        }

        return $this->stringifier->stringify($value);
    }
}
