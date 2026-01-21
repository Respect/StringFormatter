<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
-->

# Creating Custom Modifiers

Create custom modifiers by implementing the `Modifier` interface.

## The Modifier Interface

```php
namespace Respect\StringFormatter;

interface Modifier
{
    public function modify(mixed $value, string|null $pipe): string;
}
```

- `$value`: The placeholder value to transform
- `$pipe`: The modifier name from the template (e.g., `"upper"` in `{{name|upper}}`)

## Basic Example

```php
use Respect\StringFormatter\Modifier;

final readonly class UppercaseModifier implements Modifier
{
    public function __construct(
        private Modifier $nextModifier,
    ) {
    }

    public function modify(mixed $value, string|null $pipe): string
    {
        if ($pipe === 'upper') {
            return strtoupper($this->nextModifier->modify($value, null));
        }

        return $this->nextModifier->modify($value, $pipe);
    }
}
```

## Usage

```php
use Respect\StringFormatter\PlaceholderFormatter;
use Respect\StringFormatter\Modifiers\StringifyModifier;

$formatter = new PlaceholderFormatter(
    ['name' => 'John'],
    new UppercaseModifier(new StringifyModifier()),
);

echo $formatter->format('Hello {{name|upper}}');
// Output: Hello JOHN
```

## Modifiers with Parameters

Use `:` to pass parameters in the pipe:

```php
final readonly class TruncateModifier implements Modifier
{
    public function __construct(
        private Modifier $nextModifier,
    ) {
    }

    public function modify(mixed $value, string|null $pipe): string
    {
        if ($pipe !== null && str_starts_with($pipe, 'truncate:')) {
            $length = (int) substr($pipe, 9);
            $string = $this->nextModifier->modify($value, null);

            return strlen($string) > $length
                ? substr($string, 0, $length) . '...'
                : $string;
        }

        return $this->nextModifier->modify($value, $pipe);
    }
}
```

```php
$formatter = new PlaceholderFormatter(
    ['text' => 'This is a very long piece of text'],
    new TruncateModifier(new StringifyModifier()),
);

echo $formatter->format('{{text|truncate:10}}');
// Output: This is a...
```

## Key Points

1. **Always chain to next modifier** - Call `$this->nextModifier->modify()` for unhandled pipes
2. **Handle null pipes** - `$pipe` is `null` when no modifier is specified
3. **Type safety** - Handle various input types gracefully
