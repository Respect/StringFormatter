<?php

declare(strict_types=1);

namespace Respect\StringFormatter;

interface Formatter
{
    public function format(string $input): string;
}
