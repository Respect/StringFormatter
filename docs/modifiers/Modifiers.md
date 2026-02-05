<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
-->

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
use Respect\StringFormatter\Modifiers\RawModifier;
use Respect\StringFormatter\Modifiers\StringifyModifier;

$formatter = new PlaceholderFormatter(
    ['name' => 'John', 'active' => true],
    new RawModifier(new StringifyModifier()),
);

echo $formatter->format('Hello {{name}}');
// Output: Hello "John"

echo $formatter->format('Hello {{name|raw}}');
// Output: Hello John
```

If no modifier is provided, the formatter uses `StringifyModifier` by default.

## Available Modifiers

- **[FormatterModifier](FormatterModifier.md)** - Enables using any formatter as a modifier (e.g., `date:Y-m-d`, `number:2`)
- **[ListModifier](ListModifier.md)** - Formats arrays as human-readable lists with conjunctions
- **[QuoteModifier](QuoteModifier.md)** - Quotes string values using a stringifier quoter
- **[RawModifier](RawModifier.md)** - Returns scalar values as raw strings with `|raw` pipe
- **[StringifyModifier](StringifyModifier.md)** - Always converts values to strings (default)
- **[StringPassthroughModifier](StringPassthroughModifier.md)** - Returns strings unchanged, delegates non-strings to next modifier
- **[TransModifier](TransModifier.md)** - Translates string values using a Symfony translator

## Creating Custom Modifiers

See [Creating Custom Modifiers](CreatingCustomModifiers.md) for implementing your own modifiers.
