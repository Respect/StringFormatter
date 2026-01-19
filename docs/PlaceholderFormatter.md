# PlaceholderFormatter

The `PlaceholderFormatter` replaces `{{placeholder}}` markers in strings with values from a parameters array. All non-string values are converted to strings using a `Stringifier` instance.

## Usage

### Basic Usage

```php
use Respect\StringFormatter\PlaceholderFormatter;
use Respect\Stringifier\HandlerStringifier;

$formatter = new PlaceholderFormatter(['name' => 'John', 'age' => 30]);

echo $formatter->format('Hello {{name}}, you are {{age}} years old.');
// Outputs: "Hello John, you are 30 years old."
```

### With Custom Stringifier

```php
use Respect\StringFormatter\PlaceholderFormatter;
use Respect\Stringifier\HandlerStringifier;

$stringifier = HandlerStringifier::create();

$formatter = new PlaceholderFormatter(
    ['name' => 'John', 'items' => ['a', 'b', 'c']],
    $stringifier
);

echo $formatter->format('Hello {{name}}, items: {{items}}');
// Outputs: "Hello John, items: [a, b, c]"
```

### Using Additional Parameters

The `formatWith` method allows passing additional parameters at format time. Constructor parameters take precedence and won't be overwritten.

```php
use Respect\StringFormatter\PlaceholderFormatter;

// Create formatter with base parameters
$formatter = new PlaceholderFormatter(['siteName' => 'MyApp', 'year' => 2024]);

// Add additional parameters at format time
echo $formatter->formatWith(
    'Welcome to {{siteName}} - Hello {{userName}}! © {{year}}',
    ['userName' => 'John', 'year' => 2020] // year won't override constructor value
);
// Outputs: "Welcome to MyApp - Hello John! © 2024"
```

## API

### `PlaceholderFormatter::__construct`

- `__construct(array $parameters, Stringifier|null $stringifier = null)`

Creates a new formatter instance with the specified parameters and stringifier.

**Parameters:**

- `$parameters`: Associative array of placeholder names to values
- `$stringifier`: Stringifier instance for converting all non-string values to strings. If `null`, it creates its own stringifier.

### `format`

- `format(string $input): string`

Formats the template string by replacing `{{placeholder}}` syntax with corresponding parameter values.

**Parameters:**

- `$input`: The template string containing placeholders

**Returns:** The formatted string with placeholders replaced by their values

### `formatWith`

- `formatWith(string $input, array $parameters): string`

Formats the template string with additional parameters merged with constructor parameters. Constructor parameters take precedence and won't be overwritten by additional parameters.

**Parameters:**

- `$input`: The template string containing placeholders
- `$parameters`: Additional associative array of placeholder names to values

**Returns:** The formatted string with placeholders replaced by their values

**Behavior:**

- Additional parameters are merged with constructor parameters
- Constructor parameters always take precedence (cannot be overwritten)
- Useful for adding context-specific values while keeping base values consistent

## Template Syntax

### Placeholder Format

Placeholders follow the format `{{name}}` where `name` is a valid parameter key.

**Rules:**

- Placeholder names must match the regex pattern `\w+` (word characters: letters, digits, underscore)
- Names are case-sensitive: `{{Name}}` and `{{name}}` are different placeholders
- Placeholders can appear multiple times in the template
- Whitespace inside braces is not allowed: `{{ name }}` will not match

**Valid placeholders:**

- `{{name}}`
- `{{firstName}}`
- `{{value123}}`
- `{{user_id}}`

**Invalid placeholders (treated as literals):**

- `{name}` (single braces)
- `{{ name }}` (contains spaces)
- `{{first-name}}` (contains hyphen)
- `{{}}` (empty)

## Type Handling

The formatter uses the injected `Stringifier` to convert all parameter values to strings:

| Type       | Behavior                                                       | Example                                               |
| ---------- | -------------------------------------------------------------- | ----------------------------------------------------- |
| `string`   | Used as-is                                                     | `"hello"` → `"hello"`                                 |
| `int`      | Converted using stringifier                                    | `42` → `"42"`                                         |
| `float`    | Converted using stringifier                                    | `19.99` → `"19.99"`                                   |
| `bool`     | Converted using stringifier with backticks                     | `true` → `` `true` ``, `false` → `` `false` ``        |
| `null`     | Converted using stringifier with backticks                     | `null` → `` `null` ``                                 |
| `array`    | Converted using stringifier (or `print_r` as fallback)         | `[1, 2]` → `"[1, 2]"` (varies)                        |
| `object`   | Converted using stringifier (or `print_r` as fallback)         | Varies by object type                                 |
| Stringable | Converted using stringifier (includes metadata with backticks) | `__toString()` → `` `Stringable@anonymous { ... }` `` |
| Resource   | Converted using stringifier (or `print_r` as fallback)         | Resource representation                               |
| Missing    | Keeps placeholder unchanged (parameter key doesn't exist)      | N/A → `"{{name}}"`                                    |

## Behavior

### Successful Replacement

When a placeholder name exists as a parameter key:

- The placeholder is replaced with the stringified value (using the injected `Stringifier`)
- String values are used as-is without stringification
- All non-string values (including `null`) are converted using the stringifier
- Empty strings are valid replacements: `""` replaces the placeholder with nothing
- Zero values are valid: `0` and `0.0` are replaced with their string representations

### Placeholder Preservation

Placeholders are kept unchanged (as literal text) when:

- The parameter key doesn't exist in the parameters array
- The placeholder syntax is malformed (e.g., single braces, spaces inside braces)

### Null Value Handling

**Important:** Unlike some template engines, `null` values are **converted to the string `` `null` ``** (with backticks) rather than preserving the placeholder or using an empty string. This ensures explicit representation of null values in the output.

### Edge Cases

- **Empty template**: Returns empty string
- **No placeholders**: Returns template unchanged
- **Empty parameters**: All placeholders remain unchanged
- **Repeated placeholders**: Each occurrence is replaced independently with the same value
- **Unicode support**: Full support for Unicode in template text, placeholder names, and values

## Examples

### Basic Examples

| Parameters             | Template                    | Output                |
| ---------------------- | --------------------------- | --------------------- |
| `['name' => 'John']`   | `"Hello {{name}}!"`         | `"Hello John!"`       |
| `['x' => 1, 'y' => 2]` | `"{{x}} + {{y}} = 3"`       | `"1 + 2 = 3"`         |
| `['name' => 'Alice']`  | `"{{name}} loves {{name}}"` | `"Alice loves Alice"` |
| `['value' => '']`      | `"Value: [{{value}}]"`      | `"Value: []"`         |
| `['count' => 0]`       | `"Count: {{count}}"`        | `"Count: 0"`          |

### Missing and Null Values

| Parameters                 | Template                          | Output                        |
| -------------------------- | --------------------------------- | ----------------------------- |
| `['name' => 'John']`       | `"{{name}} is {{age}} years old"` | `"John is {{age}} years old"` |
| `['name' => null]`         | `"Hello {{name}}"`                | ``"Hello `null`"``            |
| `[]`                       | `"Hello {{name}}"`                | `"Hello {{name}}"`            |
| `['a' => 'A', 'c' => 'C']` | `"{{a}}-{{b}}-{{c}}"`             | `"A-{{b}}-C"`                 |

### Type Conversions

| Parameters            | Template               | Output                |
| --------------------- | ---------------------- | --------------------- |
| `['count' => 42]`     | `"Count: {{count}}"`   | `"Count: 42"`         |
| `['price' => 19.99]`  | `"Price: ${{price}}"`  | `"Price: $19.99"`     |
| `['active' => true]`  | `"Active: {{active}}"` | ``"Active: `true`"``  |
| `['active' => false]` | `"Active: {{active}}"` | ``"Active: `false`"`` |

### formatWith Examples

| Constructor Params   | Additional Params      | Template                 | Output                    |
| -------------------- | ---------------------- | ------------------------ | ------------------------- |
| `['name' => 'John']` | `['age' => 30]`        | `"{{name}} is {{age}}"`  | `"John is 30"`            |
| `['name' => 'John']` | `['name' => 'Jane']`   | `"Hello {{name}}"`       | `"Hello John"` (not Jane) |
| `['app' => 'MyApp']` | `['user' => 'Bob']`    | `"{{app}}: Hi {{user}}"` | `"MyApp: Hi Bob"`         |
| `[]`                 | `['x' => 1, 'y' => 2]` | `"{{x}} + {{y}}"`        | `"1 + 2"`                 |

### Malformed Placeholders

| Parameters           | Template                 | Output                   |
| -------------------- | ------------------------ | ------------------------ |
| `['name' => 'John']` | `"Hello {name}"`         | `"Hello {name}"`         |
| `['name' => 'John']` | `"Hello {{ name }}"`     | `"Hello {{ name }}"`     |
| `['name' => 'John']` | `"Hello {{{name}}}"`     | `"Hello {John}"`         |
| `['name' => 'John']` | `"Hello {{}}"`           | `"Hello {{}}"`           |
| `['name' => 'John']` | `"Hello {{first-name}}"` | `"Hello {{first-name}}"` |

### Unicode Support

| Parameters                 | Template               | Output           |
| -------------------------- | ---------------------- | ---------------- |
| `['name' => 'José']`       | `"Hola {{name}}!"`     | `"Hola José!"`   |
| `['greeting' => 'Привет']` | `"{{greeting}} World"` | `"Привет World"` |
| `['emoji' => '🎉']`        | `"Party {{emoji}}"`    | `"Party 🎉"`     |
| `['text' => '你好']`       | `"{{text}}，世界"`     | `"你好，世界"`   |

## Use Cases

### Email Templates

```php

$formatter = new PlaceholderFormatter([
    'customerName' => 'Bob Smith',
    'orderNumber' => 'ORD-2024-001',
    'total' => 149.99
]);

$email = $formatter->format(<<<'EMAIL'
Dear {{customerName}},

Thank you for your order {{orderNumber}}.
Total amount: ${{total}}

We will process your order shortly.
EMAIL);
```

### Log Messages

```php

$formatter = new PlaceholderFormatter([
    'user' => 'admin',
    'action' => 'login',
    'ip' => '192.168.1.100',
    'timestamp' => '2024-01-18 10:30:45'
]);

echo $formatter->format('[{{timestamp}}] User {{user}} performed {{action}} from {{ip}}');
// Outputs: "[2024-01-18 10:30:45] User admin performed login from 192.168.1.100"
```

### Notification Messages

```php

$formatter = new PlaceholderFormatter([
    'count' => 3,
    'type' => 'comments',
    'post' => 'Introduction to PHP'
]);

echo $formatter->format('You have {{count}} new {{type}} on "{{post}}"');
// Outputs: "You have 3 new comments on "Introduction to PHP""
```

### URL Generation

```php

$formatter = new PlaceholderFormatter([
    'scheme' => 'https',
    'domain' => 'api.example.com',
    'version' => 'v1',
    'resource' => 'users',
    'id' => 12345
]);

echo $formatter->format('{{scheme}}://{{domain}}/{{version}}/{{resource}}/{{id}}');
// Outputs: "https://api.example.com/v1/users/12345"
```

### Dynamic Content

```php

$formatter = new PlaceholderFormatter([
    'siteName' => 'MyApp',
    'year' => 2024,
    'version' => '2.1.0'
]);

echo $formatter->format('Welcome to {{siteName}} v{{version}} - © {{year}}');
// Outputs: "Welcome to MyApp v2.1.0 - © 2024"
```

### Reusable Templates with Context

Using `formatWith` to create reusable formatters with context-specific values:

```php
use Respect\StringFormatter\PlaceholderFormatter;

// Create a reusable formatter with common parameters
$emailFormatter = new PlaceholderFormatter([
    'companyName' => 'Acme Corp',
    'supportEmail' => 'support@acme.com',
    'year' => 2024
]);

// Use with different recipients
$email1 = $emailFormatter->formatWith(
    'Dear {{customerName}}, thank you for contacting {{companyName}}. Reply to {{supportEmail}}.',
    ['customerName' => 'Alice']
);
// Outputs: "Dear Alice, thank you for contacting Acme Corp. Reply to support@acme.com."

$email2 = $emailFormatter->formatWith(
    'Dear {{customerName}}, thank you for contacting {{companyName}}. Reply to {{supportEmail}}.',
    ['customerName' => 'Bob']
);
// Outputs: "Dear Bob, thank you for contacting Acme Corp. Reply to support@acme.com."
```

## International Support

The formatter fully supports Unicode characters in templates, placeholder names, and values:

```php

$formatter = new PlaceholderFormatter([
    'nome' => 'João',
    'cidade' => 'São Paulo'
]);

echo $formatter->format('{{nome}} mora em {{cidade}}');
// Outputs: "João mora em São Paulo"

$formatter = new PlaceholderFormatter([
    'greeting' => 'مرحبا',
    'name' => 'أحمد'
]);

echo $formatter->format('{{greeting}} {{name}}');
// Outputs: "مرحبا أحمد"
```

## Limitations

- **No nested placeholders**: `{{outer{{inner}}}}` is not supported
- **No expressions**: `{{x + y}}` is not evaluated; only simple value replacement
- **No conditional logic**: No if/else or ternary operations
- **No default values**: Use null checks in PHP before passing parameters

## Future Extensions

The implementation is designed to support modifiers in a future phase:

```php
// Future syntax (not yet implemented)
$formatter->format('Hello {{name|upper}}!');
// Would output: "Hello JOHN!"
```

The regex pattern and internal structure are prepared for this extension without breaking changes.
