<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter\Modifiers;

use InvalidArgumentException;
use Respect\StringFormatter\Throwable;

final class InvalidModifierPipeException extends InvalidArgumentException implements Throwable
{
}
