<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
-->

# PlaceholderFormatter

The `PlaceholderFormatter` replaces `{{placeholder}}` markers in strings with values from a parameters array.

## Usage

```php
use Respect\StringFormatter\PlaceholderFormatter;

$formatter = new PlaceholderFormatter(['name' => 'John', 'age' => 30]);

echo $formatter->format('Hello {{name}}, you are {{age}} years old.');
// Outputs: Hello John, you are 30 years old.
```

### Using Additional Parameters

The `formatUsing` method allows passing additional parameters at format time. Constructor parameters take precedence and won't be overwritten.

```php
$formatter = new PlaceholderFormatter(['siteName' => 'MyApp', 'year' => 2024]);

echo $formatter->formatUsing(
    'Welcome to {{siteName}} - Hello {{userName}}! © {{year}}',
    ['userName' => 'John', 'year' => 2020] // year won't override constructor value
);
// Outputs: Welcome to MyApp - Hello John! © 2024
```

### With Modifiers

Placeholders can include modifiers that transform values. Formatters can be used as modifiers using the pipe syntax.

```php
$formatter = new PlaceholderFormatter([
    'date' => '2024-01-15',
    'amount' => '1234.567',
    'phone' => '1234567890',
]);

// Date formatting
echo $formatter->format('Date: {{date|date:Y/m/d}}');
// Output: Date: 2024/01/15

// Number formatting
echo $formatter->format('Amount: ${{amount|number:2}}');
// Output: Amount: $1,234.57

// Pattern formatting
echo $formatter->format('Phone: {{phone|pattern:(###) ###-####}}');
// Output: Phone: (123) 456-7890
```

See the [FormatterModifier](modifiers/FormatterModifier.md) documentation for all available formatters and options.

#### Multiple Pipes

You can chain multiple modifiers together using the pipe (`|`) character. Modifiers are applied sequentially from left to right.

```php
$formatter = new PlaceholderFormatter([
    'phone' => '1234567890',
    'value' => '12345',
]);

// Apply pattern formatting, then mask sensitive data
echo $formatter->format('Phone: {{phone|pattern:(###) ###-####|mask:6-12}}');
// Output: Phone: (123) ******90

// Apply number formatting, then mask
echo $formatter->format('Value: {{value|number:0|mask:1-3}}');
// Output: Value: ***45
```

**Escaped Pipes:** If you need to use the pipe character (`|`) as part of a modifier argument (not as a separator), escape it with a backslash (`\|`):

```php
$formatter = new PlaceholderFormatter(['value' => '123456']);

// Escaped pipe in pattern, then apply mask
echo $formatter->format('{{value|pattern:###\|###|mask:1-3}}');
// Output: ***|456
```

You can also use other modifiers like `list` and `trans`:

```php
$formatter = new PlaceholderFormatter([
    'items' => ['apple', 'banana', 'cherry'],
]);

echo $formatter->format('Items: {{items|list:and}}');
// Output: Items: apple, banana, and cherry
```

See the [Modifiers](modifiers/Modifiers.md) documentation for details.

## API

### `__construct(array $parameters, Modifier|null $modifier = null)`

Creates a new formatter instance.

- `$parameters`: Associative array of placeholder names to values
- `$modifier`: Optional modifier chain. If `null`, uses default modifiers.

### `format(string $input): string`

Formats the template string by replacing placeholders with parameter values.

### `formatUsing(string $input, array $parameters): string`

Formats with additional parameters merged with constructor parameters. Constructor parameters take precedence.

## Template Syntax

Placeholders follow the format `{{name}}` where `name` is a valid parameter key. Modifiers can be added after a pipe: `{{name|modifier}}`. Multiple modifiers can be chained: `{{name|modifier1|modifier2}}`.

**Rules:**

- Names must match `\w+` (letters, digits, underscore)
- Names are case-sensitive
- No whitespace inside braces or around the pipe
- Multiple pipes are separated by `|` and applied sequentially
- Escaped pipes (`\|`) within modifiers are treated as literal characters, not separators

**Valid:** `{{name}}`, `{{user_id}}`, `{{name|raw}}`, `{{value|date:Y-m-d|mask:1-5}}`

**Invalid:** `{name}`, `{{ name }}`, `{{first-name}}`, `{{}}`

## Behavior

- **Missing parameters**: Placeholders are kept unchanged
- **Null values**: Converted to `` `null` `` string representation
- **Empty strings**: Valid replacements (placeholder becomes empty)
- **Repeated placeholders**: Each occurrence replaced independently
- **Unicode**: Fully supported in templates and values

## Examples

| Parameters             | Template                | Output              |
| ---------------------- | ----------------------- | ------------------- |
| `['name' => 'John']`   | `"Hello {{name}}!"`     | `Hello John!`       |
| `['x' => 1, 'y' => 2]` | `"{{x}} + {{y}}"`       | `1 + 2`             |
| `['name' => 'John']`   | `"{{name}} is {{age}}"` | `John is {{age}}`   |
| `[]`                   | `"Hello {{name}}"`      | `Hello {{name}}`    |
| `['active' => true]`   | `"Active: {{active}}"`  | ``Active: `true` `` |

## Limitations

- No nested placeholders: `{{outer{{inner}}}}`
- No expressions: `{{x + y}}`
- No conditional logic
- No default values syntax
