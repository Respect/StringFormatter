<?php

declare(strict_types=1);

namespace Respect\StringFormatter\Modifiers;

use Respect\StringFormatter\Modifier;
use Respect\Stringifier\HandlerStringifier;
use Respect\Stringifier\Stringifier;

use function sprintf;

final readonly class StringifyModifier implements Modifier
{
    private Stringifier $stringifier;

    public function __construct(
        Stringifier|null $stringifier = null,
    ) {
        $this->stringifier = $stringifier ?? HandlerStringifier::create();
    }

    public function modify(mixed $value, string|null $pipe): string
    {
        if ($pipe !== null) {
            throw new InvalidModifierPipeException(sprintf('"%s" is not recognized as a valid pipe', $pipe));
        }

        return $this->stringifier->stringify($value);
    }
}
