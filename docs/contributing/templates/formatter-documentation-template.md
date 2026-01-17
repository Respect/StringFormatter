# FormatterDocumentationTemplate

# [FormatterName]

The `[FormatterName]` enables [brief description of what the formatter does].

## Usage

### Basic Usage

```php
use Respect\StringFormatter\[FormatterName];

$formatter = new [FormatterName]([parameters if any]);

echo $formatter->format('[input string]');
// Outputs: '[formatted result]'
```

### [Additional Usage Examples]

```php
use Respect\StringFormatter\[FormatterName];

$formatter = new [FormatterName]([different parameters]);

echo $formatter->format('[different input string]');
// Outputs: '[different formatted result]'
```

## API

### `[FormatterName]::__construct`

- `__construct([constructor parameters])`

Creates a new formatter instance with the specified [description of parameters].

**Parameters:**

- `$param1`: [description of parameter]
- `$param2`: [description of parameter, with default if applicable]

**Throws:** `[ExceptionType]` when [condition that causes exception]

### `format`

- `format(string $input): string`

Formats the input string according to [description of formatting rules].

**Parameters:**

- `$input`: The string to format

**Returns:** The formatted string

## [Advanced Features/Syntax]

[If your formatter has special syntax, patterns, or complex functionality, document it here with tables and examples]

| Pattern   | Description   | Example   |
| --------- | ------------- | --------- |
| [pattern] | [description] | [example] |

## Examples

| Configuration | Input   | Output   |
| ------------- | ------- | -------- |
| [config]      | [input] | [output] |

## Notes

[Any additional considerations, edge cases, performance notes, etc.]
