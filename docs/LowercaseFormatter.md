<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
-->

# LowercaseFormatter

The `LowercaseFormatter` converts strings to lowercase with proper UTF-8 character support for international text.

## Usage

### Basic Usage

```php
use Respect\StringFormatter\LowercaseFormatter;

$formatter = new LowercaseFormatter();

echo $formatter->format('HELLO WORLD');
// Outputs: "hello world"
```

### Unicode Characters

```php
use Respect\StringFormatter\LowercaseFormatter;

$formatter = new LowercaseFormatter();

echo $formatter->format('CAFÉ FRANÇAIS');
// Outputs: "café français"

echo $formatter->format('コンニチハ');
// Outputs: "コンニチハ"
```

### Mixed Content

```php
use Respect\StringFormatter\LowercaseFormatter;

$formatter = new LowercaseFormatter();

echo $formatter->format('HELLO WORLD 😊');
// Outputs: "hello world 😊"
```

## API

### `LowercaseFormatter::__construct`

- `__construct()`

Creates a new lowercase formatter instance.

### `format`

- `format(string $input): string`

Converts the input string to lowercase using UTF-8 aware conversion.

**Parameters:**

- `$input`: The string to convert to lowercase

**Returns:** The lowercase string

## Examples

| Input        | Output       | Description                   |
| ------------ | ------------ | ----------------------------- |
| `HELLO`      | `hello`      | Simple ASCII text             |
| `CAFÉ`       | `café`       | Latin characters with accents |
| `ПРИВЕТ`     | `привет`     | Cyrillic text                 |
| `コンニチハ` | `コンニチハ` | Japanese text                 |
| `HELLO 😊`   | `hello 😊`   | Text with emoji               |
| `ÉÎÔÛ`       | `éîôû`       | Multiple accented characters  |

## Notes

- Uses `mb_strtolower()` for proper Unicode handling
- Preserves accent marks and diacritical marks
- Works with all Unicode scripts (Latin, Cyrillic, Greek, CJK, etc.)
- Emoji and special symbols are preserved unchanged
- Combining diacritics are properly handled
- Numbers and special characters remain unchanged
- Empty strings return empty strings
