<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
-->

# TrimFormatter

The `TrimFormatter` removes characters from the edges of strings with configurable characters and side selection, fully supporting UTF-8 Unicode characters.

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

$formatter = new TrimFormatter('left');

echo $formatter->format('  hello  ');
// Outputs: "hello  "

$formatterRight = new TrimFormatter('right');

echo $formatterRight->format('  hello  ');
// Outputs: "  hello"
```

### Custom Characters

```php
use Respect\StringFormatter\TrimFormatter;

$formatter = new TrimFormatter('both', '-._');

echo $formatter->format('---hello---');
// Outputs: "hello"

echo $formatter->format('._hello_._');
// Outputs: "hello"
```

### Unicode Characters

```php
use Respect\StringFormatter\TrimFormatter;

// CJK full-width spaces are trimmed by default
$formatter = new TrimFormatter();

echo $formatter->format('　hello世界　');
// Outputs: "hello世界"

// Trim emoji with custom characters
$formatterEmoji = new TrimFormatter('both', '😊');

echo $formatterEmoji->format('😊hello😊');
// Outputs: "hello"
```

## API

### `TrimFormatter::__construct`

- `__construct(string $side = "both", string|null $characters = null)`

Creates a new trim formatter instance.

**Parameters:**

- `$side`: Which side(s) to trim: "left", "right", or "both" (default: "both")
- `$characters`: The characters to trim from the string edges, or `null` for default Unicode whitespace (default: `null`)

**Throws:** `InvalidFormatterException` when `$side` is not "left", "right", or "both"

### `format`

- `format(string $input): string`

Removes characters from the specified side(s) of the input string.

**Parameters:**

- `$input`: The string to trim

**Returns:** The trimmed string

## Examples

| Side      | Characters     | Input           | Output       | Description                         |
| --------- | -------------- | --------------- | ------------ | ----------------------------------- |
| `"both"`  | `null`         | `"  hello  "`   | `"hello"`    | Trim default whitespace both sides  |
| `"left"`  | `null`         | `"  hello  "`   | `"hello  "`  | Trim default whitespace left only   |
| `"right"` | `null`         | `"  hello  "`   | `"  hello"`  | Trim default whitespace right only  |
| `"both"`  | `"-"`          | `"---hello---"` | `"hello"`    | Trim hyphens from both sides        |
| `"both"`  | `"-._"`        | `"-._hello_.-"` | `"hello"`    | Trim multiple custom characters     |
| `"left"`  | `":"`          | `":::hello:::"` | `"hello:::"` | Trim colons from left only          |
| `"both"`  | `null`         | `"　hello"`     | `"hello"`    | CJK space trimmed by default        |
| `"both"`  | `"😊"`         | `"😊hello😊"`   | `"hello"`    | Trim emoji with custom characters   |

## Notes

- Uses PHP's `mb_trim`, `mb_ltrim`, and `mb_rtrim` functions for multibyte-safe trimming
- Fully UTF-8 aware - handles all Unicode scripts including CJK, emoji, and complex characters
- Empty strings return empty strings
- If the characters string is empty or contains no characters present in the input, the string is returned unchanged
- Trimming operations are character-oriented, not byte-oriented

### Default Characters

When no characters are provided (`null`), the formatter uses `mb_trim`'s default which includes all Unicode whitespace characters:

**ASCII whitespace:**
- ` ` (U+0020): Ordinary space
- `\t` (U+0009): Tab
- `\n` (U+000A): New line (line feed)
- `\r` (U+000D): Carriage return
- `\0` (U+0000): NUL-byte
- `\v` (U+000B): Vertical tab
- `\f` (U+000C): Form feed

**Unicode whitespace:**
- U+00A0: No-break space
- U+1680: Ogham space mark
- U+2000–U+200A: Various width spaces (en quad, em quad, en space, em space, etc.)
- U+2028: Line separator
- U+2029: Paragraph separator
- U+202F: Narrow no-break space
- U+205F: Medium mathematical space
- U+3000: Ideographic space (CJK full-width space)
- U+0085: Next line (NEL)
- U+180E: Mongolian vowel separator

See [mb_trim documentation](https://www.php.net/manual/en/function.mb-trim.php) for the complete list.
