<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
-->

# QuoteModifier

The `|quote` modifier wraps string values with a configurable quote character (default: backtick `` ` ``).

## Behavior

- Strings are wrapped with the quote character and internal occurrences are escaped
- Non-string values delegate to the next modifier

## Usage

```php
use Respect\StringFormatter\PlaceholderFormatter;

$formatter = new PlaceholderFormatter([
    'name' => 'John',
    'text' => 'Say `hello`',
    'count' => 42,
]);

echo $formatter->format('User: {{name|quote}}');
// Output: User: `John`

echo $formatter->format('{{text|quote}}');
// Output: `Say \`hello\``

echo $formatter->format('{{count|quote}}');
// Output: 42 (delegated to next modifier)
```

## Custom Quote Character

```php
use Respect\StringFormatter\PlaceholderFormatter;
use Respect\StringFormatter\Modifiers\QuoteModifier;
use Respect\StringFormatter\Modifiers\StringifyModifier;

$formatter = new PlaceholderFormatter(
    ['name' => 'John'],
    new QuoteModifier(new StringifyModifier(), "'"),
);

echo $formatter->format('{{name|quote}}');
// Output: 'John'
```

## Examples

| Parameters           | Template          | Output       |
| -------------------- | ----------------- | ------------ |
| `['name' => 'John']` | `{{name\|quote}}` | `` `John` `` |
| `['t' => 'a`b']`     | `{{t\|quote}}`    | `` `a\`b` `` |
| `['n' => 42]`        | `{{n\|quote}}`    | `42`         |
