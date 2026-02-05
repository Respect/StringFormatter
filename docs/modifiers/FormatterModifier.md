<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
-->

# FormatterModifier

The `FormatterModifier` enables using any formatter as a placeholder modifier. It parses the pipe syntax, dynamically instantiates the requested formatter, and applies it to the value.

## Supported Formatters

| Modifier         | Example Syntax                    | Input        | Output           |
| ---------------- | --------------------------------- | ------------ | ---------------- |
| `date`           | `{{val\|date:d/m/Y}}`             | `2024-01-15` | `15/01/2024`     |
| `number`         | `{{val\|number:2:,:.}}`           | `1234.567`   | `1.234,57`       |
| `mask`           | `{{val\|mask:1-4:X}}`             | `1234567890` | `XXXX567890`     |
| `pattern`        | `{{val\|pattern:(###) ###-####}}` | `1234567890` | `(123) 456-7890` |
| `metric`         | `{{val\|metric:m}}`               | `1500`       | `1.5km`          |
| `mass`           | `{{val\|mass:g}}`                 | `1000`       | `1kg`            |
| `area`           | `{{val\|area:m^2}}`               | `10000`      | `1ha`            |
| `time`           | `{{val\|time:s}}`                 | `3600`       | `1h`             |
| `imperialLength` | `{{val\|imperialLength:in}}`      | `36`         | `1yd`            |
| `imperialMass`   | `{{val\|imperialMass:oz}}`        | `16`         | `1lb`            |
| `imperialArea`   | `{{val\|imperialArea:ft^2}}`      | `43560`      | `1ac`            |

For detailed documentation on each formatter, see the [README](../../README.md#formatters).

## Syntax

```
{{placeholder|formatterName}}
{{placeholder|formatterName:arg1}}
{{placeholder|formatterName:arg1:arg2:arg3}}
```

The formatter name is converted to a class name by capitalizing the first letter and appending "Formatter" (e.g., `date` becomes `DateFormatter`).

### Escaping Special Characters

If your formatter arguments contain `:` or `|`, escape them with a backslash:

```
{{placeholder|pattern:##\:##}}        // Pattern with colon: 12:34
{{placeholder|pattern:###\|###}}      // Pattern with pipe: 123|456
```

## Example

```php
use Respect\StringFormatter\PlaceholderFormatter;

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

- If the formatter class doesn't exist, it delegates to the next modifier in the chain
- If construction fails (invalid arguments), it delegates to the next modifier
- If formatting throws an exception, it delegates to the next modifier
- Non-string values are converted to strings before formatting

## Limitations

- Formatter arguments must be strings (they're split by `:` from the pipe)
- Complex objects or arrays cannot be passed as formatter arguments
- Formatter names must match the class name pattern (capitalized name + "Formatter")
