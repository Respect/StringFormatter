<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter\Test\Helper;

use Respect\StringFormatter\Modifier;

use function print_r;
use function sprintf;

final class TestingModifier implements Modifier
{
    public string|null $lastPipe = null;

    public function __construct(private string|null $customResult = null)
    {
    }

    public function modify(mixed $value, string|null $pipe): string
    {
        $this->lastPipe = $pipe;

        return $this->customResult ?? ($pipe ? sprintf('%s(%s)', $pipe, print_r($value, true)) : print_r($value, true));
    }
}
