# Respect\Masker

[![Build Status](https://img.shields.io/github/actions/workflow/status/Respect/Masker/continuous-integration.yml?branch=main&style=flat-square)](https://github.com/Respect/Masker/actions/workflows/continuous-integration.yml)
[![Code Coverage](https://img.shields.io/codecov/c/github/Respect/Masker?style=flat-square)](https://codecov.io/gh/Respect/Masker)
[![Latest Stable Version](https://img.shields.io/packagist/v/respect/masker.svg?style=flat-square)](https://packagist.org/packages/respect/masker)
[![Total Downloads](https://img.shields.io/packagist/dt/respect/masker.svg?style=flat-square)](https://packagist.org/packages/respect/masker)
[![License](https://img.shields.io/packagist/l/respect/masker.svg?style=flat-square)](https://packagist.org/packages/respect/masker)

A powerful and flexible PHP library for masking sensitive text data using intuitive range-based patterns.

Whether you need to hide credit card numbers, mask email addresses, or protect user privacy, Masker gives you precise control over what gets hidden and what stays visible.

## Installation

```bash
composer require respect/masker
```

## Usage

### Basic Usage

```php
use Respect\Masker\TextMasker;

$masker = new TextMasker();

echo $masker->mask('1234123412341234', '1-3,8-12');
// Outputs: ***4123*****1234
```

## API

### `mask`

- `mask(string $input, string $range): string`
- `mask(string $input, string $range, string $replacement): string`

Masks the input string according to the specified range.

**Parameters:**

- `$input`: The string to mask
- `$range`: Comma-separated range specifications
- `$replacement`: The character to use for masking (default `*`)

**Returns:** The masked string

**Throws:** `InvalidArgumentException` when invalid ranges are provided

### `isValidRange`

- `isValidRange(string $range): bool`

Validates whether the mask range specification is syntactically correct.

## Range Syntax

| Pattern | Description                     | Example               |
| ------- | ------------------------------- | --------------------- |
| `N`     | Single position (1-based)       | `3`                   |
| `N-`    | From position N to end          | `1-`                  |
| `N-M`   | Range from position N to M      | `1-3`                 |
| `-N`    | Last N characters               | `-3`                  |
| `C-M`   | From character C to character M | `1-@`                 |
| `\N`    | Escaped numeral character       | `\5`                  |
| `\C`    | Escaped special character       | `\-`, `\,`, or `\\\\` |

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

## License

This project is licensed under the ISC License - see the LICENSE file for details.
