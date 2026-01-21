# StringPassthroughModifier

The `StringPassthroughModifier` returns string values unchanged and delegates non-string values to the next modifier in the chain.

## Behavior

| Pipe  | String             | Other Types                |
| ----- | ------------------ | -------------------------- |
| (any) | Returned unchanged | Delegates to next modifier |

- Strings are returned as-is without any processing
- Non-strings are passed to the next modifier along with the pipe
- This modifier does not handle any pipes itself

This modifier is useful when you want to preserve string values while allowing non-strings to be processed by another modifier (e.g., `StringifyModifier`).

## Usage

```php
use Respect\StringFormatter\PlaceholderFormatter;
use Respect\StringFormatter\Modifiers\StringPassthroughModifier;
use Respect\StringFormatter\Modifiers\StringifyModifier;

$formatter = new PlaceholderFormatter(
    [
        'name' => 'John',
        'active' => true,
        'data' => ['x' => 1],
    ],
    new StringPassthroughModifier(new StringifyModifier()),
);

echo $formatter->format('{{name}} is {{active}}');
// Output: John is true

echo $formatter->format('Data: {{data}}');
// Output: Data: ["x":1]
```

## Examples

When used with `StringifyModifier`:

| Parameters               | Template     | Output    |
| ------------------------ | ------------ | --------- |
| `['name' => 'John']`     | `{{name}}`   | `John`    |
| `['count' => 42]`        | `{{count}}`  | `42`      |
| `['price' => 19.99]`     | `{{price}}`  | `19.99`   |
| `['active' => true]`     | `{{active}}` | `true`    |
| `['active' => false]`    | `{{active}}` | `false`   |
| `['value' => null]`      | `{{value}}`  | `null`    |
| `['items' => [1, 2, 3]]` | `{{items}}`  | `[1,2,3]` |
| `['data' => ['a' => 1]]` | `{{data}}`   | `["a":1]` |

Note that string values like `John` are returned unchanged (not quoted), while non-string values are stringified by `StringifyModifier`.
