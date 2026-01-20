# StringifyModifier

The `StringifyModifier` converts values to strings using a `Stringifier` instance. This is the default modifier used by `PlaceholderFormatter`.

## Behavior

- Strings pass through unchanged
- Other types are converted using the configured stringifier
- Throws `InvalidModifierPipeException` if an unrecognized pipe is passed

## Usage

```php
use Respect\StringFormatter\PlaceholderFormatter;

$formatter = new PlaceholderFormatter([
    'name' => 'John',
    'active' => true,
    'data' => ['x' => 1],
]);

echo $formatter->format('{{name}} is {{active}}');
// Output: John is true

echo $formatter->format('Data: {{data}}');
// Output: Data: ["x":1]
```

## Custom Stringifier

```php
use Respect\StringFormatter\PlaceholderFormatter;
use Respect\StringFormatter\Modifiers\StringifyModifier;
use Respect\Stringifier\Stringifier;

$formatter = new PlaceholderFormatter(
    ['data' => $value],
    new StringifyModifier($customStringifier),
);
```

See the [Respect\Stringifier documentation](https://github.com/Respect/Stringifier) for details on stringifiers.
