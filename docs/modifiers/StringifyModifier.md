# StringifyModifier

The `StringifyModifier` always converts values to strings, regardless of their type.

> **Note:** When used directly (without [StringPassthroughModifier](StringPassthroughModifier.md)), this modifier quotes strings. In that case, [RawModifier](RawModifier.md) can provide a `|raw` pipe for unquoted output. The default chain includes `StringPassthroughModifier`, which bypasses stringification for strings.

## Behavior

| Pipe   | Any Value        |
| ------ | ---------------- |
| (none) | Stringified      |
| Other  | Throws exception |

All values are converted to strings using the stringifier. Unlike `StringPassthroughModifier`, even strings are passed through the stringifier, which may or may not modify them.

## Usage

The `StringifyModifier` is the default modifier in `PlaceholderFormatter`. You can also create instances with custom stringifiers:

```php
use Respect\StringFormatter\PlaceholderFormatter;
use Respect\StringFormatter\Modifiers\StringifyModifier;
use Respect\Stringifier\HandlerStringifier;

$stringifier = HandlerStringifier::create();
$formatter = new PlaceholderFormatter(
    ['name' => 'John', 'active' => true, 'items' => [1, 2]],
    new StringifyModifier($stringifier),
);

echo $formatter->format('User: {{name}}');
// Output: User: "John"

echo $formatter->format('Active: {{active}}');
// Output: Active: `true`

echo $formatter->format('Items: {{items}}');
// Output: Items: `[1, 2]`
```

## Examples

| Parameters               | Template     | Output    |
| ------------------------ | ------------ | --------- |
| `['name' => 'John']`     | `{{name}}`   | `"John"`  |
| `['count' => 42]`        | `{{count}}`  | `42`      |
| `['price' => 19.99]`     | `{{price}}`  | `19.99`   |
| `['active' => true]`     | `{{active}}` | `true`    |
| `['active' => false]`    | `{{active}}` | `false`   |
| `['value' => null]`      | `{{value}}`  | `null`    |
| `['items' => [1, 2, 3]]` | `{{items}}`  | `[1,2,3]` |
| `['data' => ['a' => 1]]` | `{{data}}`   | `["a":1]` |

## Custom Stringifier

```php
use Respect\StringFormatter\Modifiers\StringifyModifier;
use Respect\Stringifier\Stringifier;

final readonly class CustomStringifier implements Stringifier
{
    public function stringify(mixed $raw): string|null
    {
        return is_bool($raw) ? ($raw ? 'YES' : 'NO') : json_encode($raw);
    }
}

$modifier = new StringifyModifier(new CustomStringifier());
echo $modifier->modify(true, null);
// Output: YES
```

## Examples

| Parameters                    | Template    | Output              |
| ----------------------------- | ----------- | ------------------- |
| `['name' => 'John']`          | `{{name}}`  | `"John"`            |
| `['count' => 42]`             | `{{count}}` | `"42"`              |
| `['on' => true]`              | `{{on}}`    | `` `true` ``        |
| `['off' => false]`            | `{{off}}`   | `` `false` ``       |
| `['items' => [1, 2]]`         | `{{items}}` | `` `[1, 2]` ``      |
| `['obj' => (object)['a'=>1]]` | `{{obj}}`   | `` `stdClass {}` `` |
| `['val' => null]`             | `{{val}}`   | `` `null` ``        |
