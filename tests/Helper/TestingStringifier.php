<?php

declare(strict_types=1);

namespace Respect\StringFormatter\Test\Helper;

use Respect\Stringifier\Stringifier;

use function uniqid;

final class TestingStringifier implements Stringifier
{
    private string $result;

    public function __construct(string|null $result = null)
    {
        $this->result = $result ?? uniqid();
    }

    public function stringify(mixed $raw): string
    {
        return $this->result;
    }
}
