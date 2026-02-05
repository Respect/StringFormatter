<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
-->

# FormatterModifier

The `FormatterModifier` enables using any formatter as a placeholder modifier. It parses the pipe syntax, dynamically instantiates the requested formatter, and applies it to the value.

## Overview

Instead of creating separate modifier classes for each formatter, `FormatterModifier` provides a unified way to use formatters as modifiers directly in placeholder templates.

## Syntax

```
{{placeholder|formatterName}}
{{placeholder|formatterName:arg1}}
{{placeholder|formatterName:arg1:arg2:arg3}}
```

The formatter name is converted to a formatter class name by:
1. Capitalizing the first letter
2. Appending "Formatter"
3. Looking in the `Respect\StringFormatter` namespace

Arguments after the formatter name (separated by `:`) are passed to the formatter's constructor.

### Escaping Special Characters

If your formatter arguments contain special characters (`:` or `|`), you can escape them with a backslash:

- Escape colons in arguments: `\:`
- Escape pipes in arguments: `\|`

```
{{placeholder|pattern:##\:##}}        // Pattern with colon: 12:34
{{placeholder|pattern:###\|###}}      // Pattern with pipe: 123|456
```

## Examples

### Date Formatting

```php
use Respect\StringFormatter\PlaceholderFormatter;

$formatter = new PlaceholderFormatter(['date' => '2024-01-15']);

// Default date format
echo $formatter->format('Date: {{date|date}}');
// Output: Date: 2024-01-15 00:00:00

// Custom date format
echo $formatter->format('Date: {{date|date:Y/m/d}}');
// Output: Date: 2024/01/15

echo $formatter->format('Date: {{date|date:F j, Y}}');
// Output: Date: January 15, 2024
```

### Number Formatting

```php
$formatter = new PlaceholderFormatter(['amount' => '1234.567']);

// Default (0 decimals)
echo $formatter->format('Amount: {{amount|number}}');
// Output: Amount: 1,235

// With decimals
echo $formatter->format('Amount: {{amount|number:2}}');
// Output: Amount: 1,234.57

// Custom separators (decimals, decimal separator, thousands separator)
echo $formatter->format('Amount: {{amount|number:2:,:  }}');
// Output: Amount: 1 234,57
```

### Mask Formatting

```php
$formatter = new PlaceholderFormatter([
    'card' => '1234567890123456',
    'ssn' => '123456789',
]);

// Mask with range
echo $formatter->format('Card: {{card|mask:5-12}}');
// Output: Card: 1234********3456

// Mask with custom replacement
echo $formatter->format('SSN: {{ssn|mask:1-5:X}}');
// Output: SSN: XXXXX6789
```

### Pattern Formatting

```php
$formatter = new PlaceholderFormatter(['phone' => '1234567890']);

echo $formatter->format('Phone: {{phone|pattern:(###) ###-####}}');
// Output: Phone: (123) 456-7890
```

### Escaping in Pattern Arguments

When your pattern contains special characters like `:` or `|`, escape them with a backslash:

```php
$formatter = new PlaceholderFormatter([
    'time' => '1234',
    'value' => '123456',
]);

// Pattern with escaped colon
echo $formatter->format('Time: {{time|pattern:##\:##}}');
// Output: Time: 12:34

// Pattern with escaped pipe
echo $formatter->format('Value: {{value|pattern:###\|###}}');
// Output: Value: 123|456
```

### Metric Formatting

```php
$formatter = new PlaceholderFormatter(['distance' => '1500']);

echo $formatter->format('Distance: {{distance|metric:mm}}');
// Output: Distance: 1.5 m
```

### Multiple Formatters

```php
$formatter = new PlaceholderFormatter([
    'date' => '2024-01-15',
    'amount' => '1234.56',
    'phone' => '1234567890',
]);

$template = <<<'TEMPLATE'
Date: {{date|date:d/m/Y}}
Amount: ${{amount|number:2}}
Phone: {{phone|pattern:(###) ###-####}}
TEMPLATE;

echo $formatter->format($template);
// Output:
// Date: 15/01/2024
// Amount: $1,234.56
// Phone: (123) 456-7890
```

## Behavior

### Formatter Resolution

1. The modifier attempts to instantiate a formatter based on the pipe name
2. If the formatter class doesn't exist, it delegates to the next modifier in the chain
3. If the formatter exists but construction fails (invalid arguments), it delegates to the next modifier

### Value Conversion

- If the value is already a string, it's passed directly to the formatter
- If the value is not a string, it's first converted to a string by delegating to the next modifier
- This ensures formatters always receive valid string input

### Error Handling

- If a formatter throws an exception during formatting, the modifier delegates to the next modifier
- This provides graceful fallback behavior

### Fallback Chain

`FormatterModifier` is designed to work in a chain with other modifiers:

```
FormatterModifier → TransModifier → ListModifier → StringPassthroughModifier → StringifyModifier
```

If a pipe name doesn't match a formatter, it falls through to the next modifier (e.g., `trans`, `list:and`, `quote`).

## Integration

`FormatterModifier` is automatically included in the default modifier chain for `PlaceholderFormatter`. You don't need to configure it explicitly.

If you want to customize the modifier chain, you can include it manually:

```php
use Respect\StringFormatter\PlaceholderFormatter;
use Respect\StringFormatter\Modifiers\FormatterModifier;
use Respect\StringFormatter\Modifiers\StringifyModifier;

$formatter = new PlaceholderFormatter(
    ['value' => '123'],
    new FormatterModifier(new StringifyModifier())
);
```

## Supported Formatters

All formatters in the `Respect\StringFormatter` namespace can be used:

- `date` - [DateFormatter](../DateFormatter.md)
- `number` - [NumberFormatter](../NumberFormatter.md)
- `mask` - [MaskFormatter](../MaskFormatter.md)
- `pattern` - [PatternFormatter](../PatternFormatter.md)
- `metric` - [MetricFormatter](../MetricFormatter.md)
- `mass` - [MassFormatter](../MassFormatter.md)
- `area` - [AreaFormatter](../AreaFormatter.md)
- `time` - [TimeFormatter](../TimeFormatter.md)
- `imperialLength` - [ImperialLengthFormatter](../ImperialLengthFormatter.md)
- `imperialMass` - [ImperialMassFormatter](../ImperialMassFormatter.md)
- `imperialArea` - [ImperialAreaFormatter](../ImperialAreaFormatter.md)

## Limitations

- Formatter arguments must be strings (they're split by `:` from the pipe)
- Complex objects or arrays cannot be passed as formatter arguments
- Formatter names must match the class name pattern (capitalized name + "Formatter")
