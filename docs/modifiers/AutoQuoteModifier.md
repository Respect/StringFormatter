# AutoQuoteModifier

The `AutoQuoteModifier` automatically quotes string values by default, with the `|raw` pipe to bypass quoting.

## Behavior

| Pipe    | String           | Other Scalars              | Non-Scalar                 |
| ------- | ---------------- | -------------------------- | -------------------------- |
| (none)  | Quoted: `"John"` | Delegates to next modifier | Delegates to next modifier |
| `\|raw` | Unquoted: `John` | Returns as string          | Delegates to next modifier |

With `|raw`, booleans are converted to `1`/`0`.

## Usage

This modifier is not included in the default `PlaceholderFormatter` chain. To use it, create a chain with `StringifyModifier`:

```php
use Respect\StringFormatter\PlaceholderFormatter;
use Respect\StringFormatter\Modifiers\AutoQuoteModifier;
use Respect\StringFormatter\Modifiers\StringifyModifier;

$formatter = new PlaceholderFormatter(
    ['firstname' => 'John', 'lastname' => 'Doe', 'active' => true],
    new AutoQuoteModifier(new StringifyModifier()),
);

echo $formatter->format('Hi {{firstname}} {{lastname|raw}}');
// Output: Hi "John" Doe

echo $formatter->format('Active: {{active}}, Raw: {{active|raw}}');
// Output: Active: true, Raw: 1
```

## Examples

| Parameters            | Template         | Output   |
| --------------------- | ---------------- | -------- |
| `['name' => 'John']`  | `{{name}}`       | `"John"` |
| `['name' => 'John']`  | `{{name\|raw}}`  | `John`   |
| `['count' => 42]`     | `{{count}}`      | `42`     |
| `['count' => 42]`     | `{{count\|raw}}` | `42`     |
| `['on' => true]`      | `{{on}}`         | `true`   |
| `['on' => true]`      | `{{on\|raw}}`    | `1`      |
| `['items' => [1, 2]]` | `{{items}}`      | `[1, 2]` |
