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

Placeholders follow the format `{{name}}` where `name` is a valid parameter key. Modifiers can be added after a pipe: `{{name|modifier}}`.

**Rules:**

- Names must match `\w+` (letters, digits, underscore)
- Names are case-sensitive
- No whitespace inside braces or around the pipe

**Valid:** `{{name}}`, `{{user_id}}`, `{{name|raw}}`

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
