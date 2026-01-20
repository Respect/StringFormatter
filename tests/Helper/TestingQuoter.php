<?php

declare(strict_types=1);

namespace Respect\StringFormatter\Test\Helper;

use Respect\Stringifier\Quoter;

use function uniqid;

final class TestingQuoter implements Quoter
{
    private string $result;

    public function __construct(string|null $result = null)
    {
        $this->result = $result ?? uniqid();
    }

    public function quote(string $string, int $depth): string
    {
        return $this->result;
    }
}
