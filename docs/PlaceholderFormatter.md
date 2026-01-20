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

Placeholders can include modifiers that transform values. See the [Modifiers](modifiers/Modifiers.md) documentation for details.

```php
$formatter = new PlaceholderFormatter(['name' => 'John']);

echo $formatter->format('Hello {{name|upper}}!');
// Outputs: Hello JOHN!
```

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
