<?php

declare(strict_types=1);

namespace Respect\StringFormatter\Modifiers;

use InvalidArgumentException;
use Respect\StringFormatter\Throwable;

final class InvalidModifierPipeException extends InvalidArgumentException implements Throwable
{
}
