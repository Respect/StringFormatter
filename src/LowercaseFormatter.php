<?php

declare(strict_types=1);

namespace Respect\StringFormatter;

use function mb_strtolower;

final readonly class LowercaseFormatter implements Formatter
{
    public function format(string $input): string
    {
        return mb_strtolower($input);
    }
}
