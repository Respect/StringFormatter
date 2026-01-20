# PlaceholderFormatter Modifiers

Modifiers transform placeholder values before they're inserted into the final string. They're applied using the `|` syntax: `{{placeholder|modifier}}`.

## How Modifiers Work

Modifiers form a chain where each modifier can:

1. **Handle the value** and return a transformed string
2. **Pass the value** to the next modifier in the chain

## Basic Usage

```php
use Respect\StringFormatter\PlaceholderFormatter;

$formatter = new PlaceholderFormatter([
    'name' => 'John',
    'items' => ['apple', 'banana'],
]);

echo $formatter->format('Hello {{name}}');
// Output: Hello John

echo $formatter->format('Items: {{items}}');
// Output: Items: ["apple","banana"]
```

## Custom Modifier Chain

You can specify a custom modifier chain when creating a `PlaceholderFormatter`:

```php
use Respect\StringFormatter\PlaceholderFormatter;
use Respect\StringFormatter\Modifiers\AutoQuoteModifier;
use Respect\StringFormatter\Modifiers\StringifyModifier;

$formatter = new PlaceholderFormatter(
    ['name' => 'John'],
    new AutoQuoteModifier(new StringifyModifier()),
);

echo $formatter->format('Hello {{name}}');
// Output: Hello "John"
```

If no modifier is provided, the formatter uses `StringifyModifier` by default.

## Available Modifiers

- **[AutoQuoteModifier](AutoQuoteModifier.md)** - Quotes string values by default, `|raw` bypasses quoting
- **[StringifyModifier](StringifyModifier.md)** - Converts values to strings (default)

## Creating Custom Modifiers

See [Creating Custom Modifiers](CreatingCustomModifiers.md) for implementing your own modifiers.
