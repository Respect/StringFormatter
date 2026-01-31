<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
-->

# DateFormatter

The `DateFormatter` parses and reformats date and time strings using flexible input parsing and configurable output formats.

## Usage

### Basic Usage

```php
use Respect\StringFormatter\DateFormatter;

$formatter = new DateFormatter();

echo $formatter->format('2024-01-15 10:30:45');
// Outputs: 2024-01-15 10:30:45
```

### Custom Format

```php
use Respect\StringFormatter\DateFormatter;

$formatter = new DateFormatter('m/d/Y');

echo $formatter->format('2024-01-15');
// Outputs: 01/15/2024
```

### European Format

```php
use Respect\StringFormatter\DateFormatter;

$formatter = new DateFormatter('d.m.Y');

echo $formatter->format('2024-01-15');
// Outputs: 15.01.2024
```

### With Month Names

```php
use Respect\StringFormatter\DateFormatter;

$formatter = new DateFormatter('l, F j, Y');

echo $formatter->format('2024-01-15');
// Outputs: Monday, January 15, 2024
```

### ISO 8601 Format

```php
use Respect\StringFormatter\DateFormatter;

$formatter = new DateFormatter('c');

echo $formatter->format('2024-01-15T10:30:45+00:00');
// Outputs: 2024-01-15T10:30:45+00:00
```

## API

### `DateFormatter::__construct`

- `__construct(string $format = 'Y-m-d H:i:s')`

Creates a new formatter instance with the specified date format.

**Parameters:**

- `$format`: PHP date format string (default: `'Y-m-d H:i:s'`)

### `format`

- `format(string $input): string`

Parses the input date string and formats it according to the formatter's configuration.

If the input cannot be parsed as a date, it is returned unchanged without modification.

**Parameters:**

- `$input`: A date string in any format parseable by `DateTime`

**Returns:** The formatted date string if the input can be parsed; the input unchanged otherwise

## Behavior

### Strict Date Validation

The formatter uses a two-level validation approach:

1. **Exception handling**: Catches `DateMalformedStringException` thrown by invalid format strings
2. **Error checking**: Uses `DateTime::getLastErrors()` to detect parsing errors and warnings

This ensures even dates that appear parseable but have parsing issues are rejected.

### Valid Input

The formatter uses PHP's `DateTime` constructor which supports various date formats including ISO 8601, MySQL format, relative formats (like "now", "tomorrow"), and other flexible formats. Valid input must parse without errors or warnings.

```php
$formatter = new DateFormatter('Y-m-d');

// Valid date input
echo $formatter->format('2024-01-15');          // Outputs: 2024-01-15
echo $formatter->format('2024-01-15 10:30:45'); // Outputs: 2024-01-15
echo $formatter->format('January 15, 2024');    // Outputs: 2024-01-15
```

### Invalid Input

When input cannot be parsed as a date or has parsing errors/warnings, the formatter returns it unchanged:

```php
$formatter = new DateFormatter('Y-m-d');

// Invalid input is returned unchanged
echo $formatter->format('invalid date');       // Outputs: invalid date
echo $formatter->format('this-is-not-a-date'); // Outputs: this-is-not-a-date
echo $formatter->format('N/A');                // Outputs: N/A
```

## Input Formats

The formatter uses PHP's `DateTime` constructor which supports various input formats:

### Standard Date Formats

| Format        | Example               |
|---------------|-----------------------|
| ISO 8601      | `2024-01-15T10:30:45` |
| MySQL         | `2024-01-15 10:30:45` |
| US Format     | `01/15/2024`          |
| European      | `15.01.2024`          |
| Unix Timestamp| `@1705331445`         |

## Output Format Strings

### Year

| Format | Description              | Example |
|--------|--------------------------|---------|
| `Y`    | 4-digit year             | 2024    |
| `y`    | 2-digit year             | 24      |

### Month

| Format | Description              | Example |
|--------|--------------------------|---------|
| `m`    | 2-digit month            | 01      |
| `n`    | Month without leading 0  | 1       |
| `F`    | Full month name          | January |
| `M`    | 3-letter month           | Jan     |

### Day

| Format | Description              | Example |
|--------|--------------------------|---------|
| `d`    | 2-digit day              | 15      |
| `j`    | Day without leading 0    | 15      |
| `D`    | 3-letter weekday         | Mon     |
| `l`    | Full weekday name        | Monday  |

### Time

| Format | Description              | Example |
|--------|--------------------------|---------|
| `H`    | 24-hour format (00-23)   | 10      |
| `h`    | 12-hour format (01-12)   | 10      |
| `i`    | Minutes (00-59)          | 30      |
| `s`    | Seconds (00-59)          | 45      |
| `A`    | AM/PM uppercase          | AM      |
| `a`    | am/pm lowercase          | am      |

### Other

| Format | Description              | Example                         |
|--------|--------------------------|---------------------------------|
| `c`    | ISO 8601                 | 2024-01-15T10:30:45+00:00       |
| `r`    | RFC 2822                 | Mon, 15 Jan 2024 10:30:45 +0000 |
| `U`    | Unix timestamp           | 1705331445                      |
| `z`    | Day of year (0-365)      | 014                             |
| `W`    | Week number (ISO-8601)   | 02                              |

## Examples

### Common Formats

| Description       | Format           | Input                   | Output                            |
|-------------------|------------------|-------------------------|-----------------------------------|
| US Date           | `m/d/Y`          | `2024-01-15`            | `01/15/2024`                      |
| European Date     | `d.m.Y`          | `2024-01-15`            | `15.01.2024`                      |
| Time Only         | `H:i:s`          | `2024-01-15 10:30:45`   | `10:30:45`                        |
| Long Format       | `l, F j, Y`      | `2024-01-15`            | `Monday, January 15, 2024`        |
| Short Format      | `M d, Y`         | `2024-01-15`            | `Jan 15, 2024`                    |
| ISO 8601          | `c`              | `2024-01-15T10:30:45`   | `2024-01-15T10:30:45+00:00`       |
| RFC 2822          | `r`              | `2024-01-15 10:30:45`   | `Mon, 15 Jan 2024 10:30:45 +0000` |

### Parsing Flexibility

The formatter intelligently parses various input formats:

```php
$formatter = new DateFormatter('Y-m-d');

// All produce the same output: 2024-01-15
echo $formatter->format('2024-01-15');           // ISO format
echo $formatter->format('01/15/2024');           // US format
echo $formatter->format('15.01.2024');           // European format
echo $formatter->format('January 15, 2024');     // Long format
```

### Relative Date Processing

```php
$formatter = new DateFormatter('l, F j, Y');

echo $formatter->format('now');       // Today's date
echo $formatter->format('tomorrow');  // Tomorrow's date
echo $formatter->format('yesterday'); // Yesterday's date
```
