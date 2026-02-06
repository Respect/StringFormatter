<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
-->

# UppercaseFormatter

The `UppercaseFormatter` converts strings to uppercase with proper UTF-8 character support for international text.

## Usage

### Basic Usage

```php
use Respect\StringFormatter\UppercaseFormatter;

$formatter = new UppercaseFormatter();

echo $formatter->format('hello world');
// Outputs: "HELLO WORLD"
```

### Unicode Characters

```php
use Respect\StringFormatter\UppercaseFormatter;

$formatter = new UppercaseFormatter();

echo $formatter->format('café français');
// Outputs: "CAFÉ FRANÇAIS"

echo $formatter->format('こんにちは');
// Outputs: "コンニチハ"
```

### Mixed Content

```php
use Respect\StringFormatter\UppercaseFormatter;

$formatter = new UppercaseFormatter();

echo $formatter->format('Hello World 😊');
// Outputs: "HELLO WORLD 😊"
```

## API

### `UppercaseFormatter::__construct`

- `__construct()`

Creates a new uppercase formatter instance.

### `format`

- `format(string $input): string`

Converts the input string to uppercase using UTF-8 aware conversion.

**Parameters:**

- `$input`: The string to convert to uppercase

**Returns:** The uppercase string

## Examples

| Input        | Output       | Description                             |
| ------------ | ------------ | --------------------------------------- |
| `hello`      | `HELLO`      | Simple ASCII text                       |
| `café`       | `CAFÉ`       | Latin characters with accents           |
| `привет`     | `ПРИВЕТ`     | Cyrillic text                           |
| `こんにちは` | `コンニチハ` | Japanese hiragana converted to katakana |
| `Hello 😊`   | `HELLO 😊`   | Text with emoji                         |
| `éîôû`       | `ÉÎÔÛ`       | Multiple accented characters            |

## Notes

- Uses `mb_strtoupper()` for proper Unicode handling
- Preserves accent marks and diacritical marks
- Works with all Unicode scripts (Latin, Cyrillic, Greek, CJK, etc.)
- Emoji and special symbols are preserved unchanged
- Combining diacritics are properly handled
- Numbers and special characters remain unchanged
- Empty strings return empty strings
