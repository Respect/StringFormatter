<?php

declare(strict_types=1);

namespace Respect\StringFormatter;

use function mb_strtoupper;

final readonly class UppercaseFormatter implements Formatter
{
    public function format(string $input): string
    {
        return mb_strtoupper($input);
    }
}
