<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
-->

# TrimFormatter

The `TrimFormatter` removes characters from the edges of strings with configurable masking and side selection, fully supporting UTF-8 Unicode characters.

## Usage

### Basic Usage

```php
use Respect\StringFormatter\TrimFormatter;

$formatter = new TrimFormatter();

echo $formatter->format('  hello world  ');
// Outputs: "hello world"
```

### Trim Specific Side

```php
use Respect\StringFormatter\TrimFormatter;

$formatter = new TrimFormatter(' ', 'left');

echo $formatter->format('  hello  ');
// Outputs: "hello  "

$formatterRight = new TrimFormatter(' ', 'right');

echo $formatterRight->format('  hello  ');
// Outputs: "  hello"
```

### Custom Mask

```php
use Respect\StringFormatter\TrimFormatter;

$formatter = new TrimFormatter('-._');

echo $formatter->format('---hello---');
// Outputs: "hello"

echo $formatter->format('._hello_._');
// Outputs: "hello"
```

### Unicode Characters

```php
use Respect\StringFormatter\TrimFormatter;

// Trim CJK full-width spaces
$formatter = new TrimFormatter('　');

echo $formatter->format('　hello世界　');
// Outputs: "hello世界"

// Trim emoji
$formatterEmoji = new TrimFormatter('😊');

echo $formatterEmoji->format('😊hello😊');
// Outputs: "hello"
```

## API

### `TrimFormatter::__construct`

- `__construct(string $mask = " \t\n\r\0\x0B", string $side = "both")`

Creates a new trim formatter instance.

**Parameters:**

- `$mask`: The characters to trim from the string edges (default: whitespace characters)
- `$side`: Which side(s) to trim: "left", "right", or "both" (default: "both")

**Throws:** `InvalidFormatterException` when `$side` is not "left", "right", or "both"

### `format`

- `format(string $input): string`

Removes characters from the specified side(s) of the input string.

**Parameters:**

- `$input`: The string to trim

**Returns:** The trimmed string

## Examples

| Configuration      | Input           | Output       | Description                     |
| ------------------ | --------------- | ------------ | ------------------------------- |
| default            | `"  hello  "`   | `"hello"`    | Trim spaces from both sides     |
| `"left"`           | `"  hello  "`   | `"hello  "`  | Trim spaces from left only      |
| `"right"`          | `"  hello  "`   | `"  hello"`  | Trim spaces from right only     |
| `"-"`              | `"---hello---"` | `"hello"`    | Trim hyphens from both sides    |
| `"-._"`            | `"-._hello_.-"` | `"hello"`    | Trim multiple custom characters |
| `":"` (`"left"`)   | `":::hello:::"` | `"hello:::"` | Trim colons from left only      |
| `"　"` (CJK space) | `"　hello"`     | `"hello"`    | Trim CJK full-width space       |
| `"😊"`             | `"😊hello😊"`   | `"hello"`    | Trim emoji                      |

## Notes

- Fully UTF-8 aware - handles all Unicode scripts including CJK, emoji, and complex characters
- Special regex characters in the mask (e.g., `.`, `*`, `?`, `+`) are automatically escaped
- Empty strings return empty strings
- If the mask is empty or contains no characters present in the input, the string is returned unchanged
- Trimming operations are character-oriented, not byte-oriented
- Combining characters are handled correctly (trimming considers the full character sequence)

### Default Mask

The default mask includes standard whitespace characters:

- Space (` `)
- Tab (`\t`)
- Newline (`\n`)
- Carriage return (`\r`)
- Null byte (`\0`)
- Vertical tab (`\x0B`)
