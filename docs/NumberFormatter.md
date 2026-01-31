<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
-->

# NumberFormatter

The `NumberFormatter` formats numeric strings with configurable thousands and decimal separators.

## Usage

### Basic Usage

```php
use Respect\StringFormatter\NumberFormatter;

$formatter = new NumberFormatter();

echo $formatter->format('1234567');
// Outputs: 1,234,567
```

### With Decimals

```php
use Respect\StringFormatter\NumberFormatter;

$formatter = new NumberFormatter(2);

echo $formatter->format('1234567.89');
// Outputs: 1,234,567.89
```

### European Format

```php
use Respect\StringFormatter\NumberFormatter;

$formatter = new NumberFormatter(
    decimals: 2,
    decimalSeparator: ',',
    thousandsSeparator: '.',
);

echo $formatter->format('1234567.89');
// Outputs: 1.234.567,89
```

### Custom Separators

```php
use Respect\StringFormatter\NumberFormatter;

$formatter = new NumberFormatter(
    decimals: 2,
    decimalSeparator: ',',
    thousandsSeparator: ' ',
);

echo $formatter->format('1234567.89');
// Outputs: 1 234 567,89
```

## API

### `NumberFormatter::__construct`

- `__construct(int $decimals = 0, string $decimalSeparator = '.', string $thousandsSeparator = ',')`

Creates a new formatter instance with the specified formatting options.

**Parameters:**

- `$decimals`: Number of decimal places to display (default: `0`)
- `$decimalSeparator`: Character to use as decimal separator (default: `.`)
- `$thousandsSeparator`: Character to use as thousands separator (default: `,`)

### `format`

- `format(string $input): string`

Formats the input numeric string according to the formatter's configuration.

If the input is not numeric, it is returned unchanged without modification.

**Parameters:**

- `$input`: A numeric string to format

**Returns:** The formatted number string if the input is numeric; the input unchanged otherwise

## Behavior

### Numeric Input

Valid numeric input includes integers, floats, negative numbers, and scientific notation. The formatter uses PHP's `number_format()` function for formatting.

### Non-Numeric Input

When input is not numeric, the formatter returns it unchanged:

```php
$formatter = new NumberFormatter(2);

// Valid numeric input
echo $formatter->format('1234.56');  // Outputs: 1,234.56

// Non-numeric input is returned unchanged
echo $formatter->format('N/A');      // Outputs: N/A
echo $formatter->format('');         // Outputs: (empty string)
echo $formatter->format('abc');      // Outputs: abc
```

## Formatting Options

### Decimal Separator

The decimal separator is applied based on the number of decimals:

| Decimals | Input  | Separator | Output   |
|----------|--------|-----------|----------|
| 0        | 1000   | (none)    | 1,000    |
| 2        | 1000   | `.`       | 1,000.00 |
| 2        | 1000   | `,`       | 1,000,00 |

### Thousands Separator

The thousands separator is applied for values of 1,000 or greater:

| Thousands | Input     | Output      |
|-----------|-----------|-------------|
| `,`       | 1234567   | 1,234,567   |
| `.`       | 1234567   | 1.234.567   |
| `' '`     | 1234567   | 1 234 567   |
| `''`      | 1234567   | 1234567     |

### Rounding Behavior

The formatter rounds to the specified number of decimal places:

| Input     | Decimals | Output   |
|-----------|----------|----------|
| 1234.5678 | 0        | 1,235    |
| 1234.5478 | 1        | 1,234.5  |
| 1234.5678 | 2        | 1,234.57 |

## Examples

### International Formats

| Format | Decimals | Decimal Sep | Thousands Sep | Input       | Output        |
|--------|----------|-------------|---------------|-------------|---------------|
| US     | 2        | `.`         | `,`           | 1234567.89  | 1,234,567.89  |
| EU     | 2        | `,`         | `.`           | 1234567.89  | 1.234.567,89  |
| Swiss  | 2        | `.`         | `'`           | 1234567.89  | 1'234'567.89  |

### Scientific Notation

The formatter accepts scientific notation:

| Input   | Output    |
|---------|-----------|
| `1e6`   | 1,000,000 |
| `1.5e3` | 1,500     |

### Negative Numbers

Negative numbers are properly formatted:

| Input      | Output       |
|------------|--------------|
| `-1234567` | -1,234,567   |
| `-1234.56` | -1,234.56    |
