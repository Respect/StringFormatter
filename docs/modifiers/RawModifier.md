# RawModifier

The `|raw` modifier returns scalar values as raw strings, converting booleans to `1`/`0`. Non-scalar values delegate to the next modifier.

> **Note:** This modifier is only useful when [StringPassthroughModifier](StringPassthroughModifier.md) is **not** in the chain. When using `StringifyModifier` directly (without `StringPassthroughModifier`), strings get quoted by default and `RawModifier` provides the `|raw` pipe for unquoted output. The default chain includes `StringPassthroughModifier`, so strings are already unquoted.

## Behavior

| Pipe    | String          | Other Scalars       | Non-Scalar                 |
| ------- | --------------- | ------------------- | -------------------------- |
| (none)  | Delegates       | Delegates           | Delegates                  |
| `\|raw` | Unquoted string | Converted to string | Delegates to next modifier |

With `|raw`, booleans are converted to `1`/`0`. All other values are delegated to the next modifier.

## Usage

The `RawModifier` is typically used with `StringifyModifier` to create a modifier chain that handles raw output:

```php
use Respect\StringFormatter\PlaceholderFormatter;
use Respect\StringFormatter\Modifiers\RawModifier;
use Respect\StringFormatter\Modifiers\StringifyModifier;

$formatter = new PlaceholderFormatter(
    ['firstname' => 'John', 'lastname' => 'Doe', 'active' => true],
    new RawModifier(new StringifyModifier()),
);

echo $formatter->format('Hi {{firstname}}');
// Output: Hi John

echo $formatter->format('Active flag: {{active|raw}}');
// Output: Active flag: 1
```

## Examples

Here are some examples demonstrating the behavior of `RawModifier` when used with `StringifyModifier`:

| Parameters                                  | Template         | Output                                        |
| ------------------------------------------- | ---------------- | --------------------------------------------- |
| `['name' => 'John']`                        | `{{name}}`       | `"John"`                                      |
| `['name' => 'John']`                        | `{{name\|raw}}`  | `John`                                        |
| `['count' => 42]`                           | `{{count}}`      | `42`                                          |
| `['count' => 42]`                           | `{{count\|raw}}` | `42`                                          |
| `['on' => true]`                            | `{{on}}`         | `true`                                        |
| `['on' => true]`                            | `{{on\|raw}}`    | `1`                                           |
| `['off' => false]`                          | `{{off}}`        | `false`                                       |
| `['off' => false]`                          | `{{off\|raw}}`   | `0`                                           |
| `['items' => [1, 2], 'list' => ['a', 'b']]` | `{{items\|raw}}` | `["a", "b"]` (delegated to StringifyModifier) |
