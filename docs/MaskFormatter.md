<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
-->

# MaskFormatter

The `MaskFormatter` enables masking sensitive text data using intuitive range-based patterns.

## Usage

### Basic Usage

```php
use Respect\StringFormatter\MaskFormatter;

$formatter = new MaskFormatter('1-3,8-12');

echo $formatter->format('1234123412341234');
// Outputs: ***4123*****1234
```

### Custom Replacement Character

```php
use Respect\StringFormatter\MaskFormatter;

$formatter = new MaskFormatter('1-3,8-12', '#');

echo $formatter->format('1234123412341234');
// Outputs: ###4123#####1234
```

## API

### `MaskFormatter::__construct`

- `__construct(string $range, string $replacement = '*')`

Creates a new formatter instance with the specified range and replacement character.

**Parameters:**

- `$range`: Comma-separated range specifications
- `$replacement`: The character to use for masking (default `*`)

**Throws:** `InvalidArgumentException` when invalid ranges are provided

### `format`

- `format(string $input): string`

Formats the input string according to the range specified in the constructor.

**Parameters:**

- `$input`: The string to format

**Returns:** The formatted string

## Range Syntax

| Pattern | Description                     | Example             |
| ------- | ------------------------------- | ------------------- |
| `N`     | Single position (1-based)       | `3`                 |
| `N-`    | From position N to end          | `1-`                |
| `N-M`   | Range from position N to M      | `1-3`               |
| `-N`    | Last N characters               | `-3`                |
| `C-M`   | From character C to character M | `1-@`               |
| `\N`    | Escaped numeral character       | `\5`                |
| `\C`    | Escaped special character       | `\-`, `\,`, or `\\` |

Multiple ranges can be specified using commas: `1-B,6-8,10`

### Numeric Positions (1-based)

Use numeric positions to mask specific characters (1-based indexing).

| Range  | Input          | Output         |
| ------ | -------------- | -------------- |
| `1-3`  | `password123`  | `***ord123`    |
| `1-3`  | `'12345'`      | `***45`        |
| `7-12` | `'1234567890'` | `123456******` |

#### Character Delimiters

Use character delimiters to mark ranges between characters in the string.

| Range  | Input                 | Output                |
| ------ | --------------------- | --------------------- |
| `1-@`  | `username@domain.com` | `********@domain.com` |
| `A-\5` | `ABCDD1234567890EFGH` | `*********567890EFGH` |
| `A-\-` | `ABCD-1234567890EFGH` | `####-1234567890EFGH` |
| `B-\,` | `ABC,DEF,GHI`         | `**C,DEF,GHI`         |

#### Multiple Ranges

Combine multiple ranges using commas to mask non-contiguous sections.

| Range        | Input                | Output                |
| ------------ | -------------------- | --------------------- |
| `1-3,8-12`   | `1234123412341234`   | `***4123*****1234`    |
| `1,3,5`      | `'12345'`            | `*2*4*`               |
| `1-3,6-8,10` | `abcdefghij`         | `***de***j`           |
| `1,3,5`      | `12345`              | `*2*4*`               |
| `1-3,8-12`   | `'123456789012'`     | `***45678******`      |
| `A-D,5-8`    | `ABCD1234567890EFGH` | `####D####567890EFGH` |
| `1-c,2-5`    | `abc123def456`       | `#####3def456`        |

#### Special Patterns

**Mask to end**: Use `1-` to mask from the beginning to the end  
**Mask last N**: Use `-N` to mask the last N characters

| Range | Input      | Output     |
| ----- | ---------- | ---------- |
| `1-`  | `12345678` | `********` |
| `-2`  | `123456`   | `1234**`   |
| `-3`  | `abcdefgh` | `abcde***` |

#### Escaping Special Characters

Escape special characters with backslash when they need to be used as literals.

| Range    | Input                        | Output                       |
| -------- | ---------------------------- | ---------------------------- |
| `3-\5`   | `1234567890`                 | `12**567890`                 |
| `1-\-`   | `email-something@domain.com` | `*****-something@domain.com` |
| `1-\\\\` | `path\to\file`               | `****\to\file`               |

To use `-`, `\` and `,` as delimiters, you must always add backslashes before them.

#### Unicode Support

Full support for UTF-8 strings including accented characters.

| Range   | Input            | Output           |
| ------- | ---------------- | ---------------- |
| `1-`    | `oftalmoscópico` | `**************` |
| `2-6,9` | `áéíóúãõçüñ`     | `á*****õç*ñ`     |
| `-4`    | `españolñç`      | `españolñ*`      |
