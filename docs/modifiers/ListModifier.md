# ListModifier

The `|list` modifier formats arrays into human-readable lists with conjunctions.

## Behavior

| Array Size | Output Format               |
| ---------- | --------------------------- |
| Empty      | Delegates to next modifier  |
| 1 item     | `apple`                     |
| 2 items    | `apple and banana`          |
| 3+ items   | `apple, banana, and cherry` |

## Pipes

- `|list` or `|list:and` - Uses :and as conjunction
- `|list:or` - Uses :or as conjunction

## Usage

```php
use Respect\StringFormatter\PlaceholderFormatter;

$formatter = new PlaceholderFormatter([
    'fruits' => ['apple', 'banana', 'cherry'],
]);

echo $formatter->format('I like {{fruits|list}}');
// Output: I like apple, banana, and cherry

echo $formatter->format('Choose {{fruits|list:or}}');
// Output: Choose apple, banana, or cherry
```

## Examples

| Parameters                     | Template              | Output        |
| ------------------------------ | --------------------- | ------------- |
| `['items' => ['a']]`           | `{{items\|list}}`     | `a`           |
| `['items' => ['a', 'b']]`      | `{{items\|list}}`     | `a and b`     |
| `['items' => ['a', 'b']]`      | `{{items\|list:or}}`  | `a or b`      |
| `['items' => ['a', 'b', 'c']]` | `{{items\|list:and}}` | `a, b, and c` |
| `['items' => ['a', 'b', 'c']]` | `{{items\|list:or}}`  | `a, b, or c`  |
