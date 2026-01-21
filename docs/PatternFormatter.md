<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
-->

# PatternFormatter

The `PatternFormatter` enables advanced pattern-based string transformation using filtering patterns and transformation directives.

## Usage

### Basic Filtering

```php
use Respect\StringFormatter\PatternFormatter;

$formatter = new PatternFormatter('000-0000');

echo $formatter->format('1234567890');
// Outputs: "123-4567"
```

### Case Transformations

```php
use Respect\StringFormatter\PatternFormatter;

$formatter = new PatternFormatter('\\l###\\U###');

echo $formatter->format('abCDEF');
// Outputs: "abcDEF"
```

### Repetition Quantifiers

```php
use Respect\StringFormatter\PatternFormatter;

// Match all digits (one or more)
$formatter = new PatternFormatter('0+');

echo $formatter->format('abc123def456');
// Outputs: "123456"

// Match all characters (zero or more)
$formatter = new PatternFormatter('#*');

echo $formatter->format('hello');
// Outputs: "hello"
```

## API

### `PatternFormatter::__construct`

- `__construct(string $pattern)`

Creates a new formatter instance with the specified pattern.

**Parameters:**

- `$pattern`: The pattern string defining transformation rules

**Throws:** `InvalidFormatterException` when pattern is empty

### `format`

- `format(string $input): string`

Formats the input string according to the pattern rules, applying filters and transformations.

**Parameters:**

- `$input`: The string to format

**Returns:** The formatted string with transformations applied

## Pattern Syntax

### Filtering Patterns

| Pattern | Description                         |
| ------- | ----------------------------------- |
| `#`     | Any character                       |
| `0`     | Digits only (0-9)                   |
| `A`     | Uppercase letters only              |
| `a`     | Lowercase letters only              |
| `C`     | Letters (upper/lower) only          |
| `W`     | Word characters (alphanumeric) only |

### Repetition Quantifiers

Filters can be followed by a repetition quantifier to match multiple characters:

| Pattern  | Description                                          |
| -------- | ---------------------------------------------------- |
| `X+`     | Match filter `X` one or more times                   |
| `X*`     | Match filter `X` zero or more times                  |
| `X{n}`   | Match filter `X` exactly `n` times                   |
| `X{n,m}` | Match filter `X` at least `n` and at most `m` times  |
| `X{n,}`  | Match filter `X` at least `n` times (no upper limit) |
| `X{,m}`  | Match filter `X` at most `m` times (zero minimum)    |

Where `X` is any filtering pattern (`#`, `0`, `A`, `a`, `C`, `W`).

**Examples:**

| Pattern   | Description                    |
| --------- | ------------------------------ |
| `0+`      | Digit one or more times        |
| `C*`      | Letter zero or more times      |
| `C{3}`    | Exactly 3 letters              |
| `A{5,10}` | Uppercase letter 5 to 10 times |
| `#{,5}`   | Any character up to 5 times    |

### Transformation Patterns

| Pattern | Description                  |
| ------- | ---------------------------- |
| `\d`    | Delete the character         |
| `\l`    | Lowercase next character     |
| `\L`    | Lowercase until `\E`         |
| `\u`    | Uppercase next character     |
| `\U`    | Uppercase until `\E`         |
| `\i`    | Invert case next character   |
| `\I`    | Invert case until `\E`       |
| `\E`    | End the transformation state |

### Escape Sequences

| Pattern | Description           | Example               |
| ------- | --------------------- | --------------------- |
| `\#`    | Literal `#` character | Matches `#` literally |
| `\0`    | Literal `0` character | Matches `0` literally |
| `\A`    | Literal `A` character | Matches `A` literally |
| `\@`    | Literal `@` character | Matches `@` literally |

### Literal Characters

Any character not defined as a pattern (`A`, `a`, `0`, `#`, `C`, `W`, `\`) is treated as a literal and appears in the output as-is.

## Behavior

### Filtering Patterns

- **Remove non-matching characters**: Characters that don't match the filter are skipped
- **Keep matching characters as-is**: When characters match the filter, they pass through unchanged
- **Consume from input**: Filters advance the input pointer when they find a match

### Transformation Patterns

- **Stateful transformations**: `\L`, `\U`, `\I` persist until reset
- **Single-character transformations**: `\d`, `\l`, `\u`, `\i` affect only the next character
- **End of transformations**: `\E` clears any active transformation state
- **Unicode aware**: Transformations work with international characters

### Repetition Quantifiers

- **Greedy matching**: Repetitions consume as many matching characters as possible up to the maximum
- **Non-matching characters skipped**: Characters that don't match the filter are skipped over
- **Works with transformations**: Repetitions can be combined with case transformations
- **Partial matches allowed**: If fewer characters match than the minimum, all available matches are returned

## Examples

| Pattern          | Input        | Output           | Description                |
| ---------------- | ------------ | ---------------- | -------------------------- |
| `000-0000`       | `1234567`    | `123-4567`       | Phone number formatting    |
| `AAA-000`        | `ABC123`     | `ABC-123`        | License plate format       |
| `\U###`          | `abc`        | `ABC`            | Uppercase until reset      |
| `\L####`         | `ABC1`       | `abc1`           | Lowercase until reset      |
| `\l#\u#`         | `Ab`         | `aB`             | Case transformation        |
| `\I####`         | `AbCd`       | `aBcD`           | Case inversion until reset |
| `CC00WW`         | `AB123D`     | `AB123D`         | International postal code  |
| `(000) 000-0000` | `1234567890` | `(123) 456-7890` | US phone format            |
| `000-00-0000`    | `123456789`  | `123-45-6789`    | SSN format                 |
| `\L##\E##`       | `ABCD`       | `abCD`           | Transformation reset       |
| `##\d##`         | `ABCDE`      | `ABDE`           | Deleting character         |
| `0+`             | `a1b2c3`     | `123`            | All digits (one or more)   |
| `C*`             | `abc123`     | `abc`            | All letters (zero or more) |
| `#{,5}`          | `abcdefgh`   | `abcde`          | Up to 5 characters         |
| `A{2,4}`         | `ABCDEFG`    | `ABCD`           | 2 to 4 uppercase letters   |
| `C+-0+`          | `ABC-123`    | `ABC-123`        | Letters then digits        |

## International Support

The formatter works with Unicode characters and international text:

```php
$formatter = new PatternFormatter('\\U##');

echo $formatter->format('ñáçé');
// Outputs: "Ñá"

$formatter = new PatternFormatter('CC');

echo $formatter->format('ñáç123');
// Outputs: "ñá"
```

## Edge Cases

| Pattern  | Input      | Output  | Reason                                       |
| -------- | ---------- | ------- | -------------------------------------------- |
| `###`    | `ab`       | `ab`    | Pattern longer than input uses all available |
| `####`   | `abcdefgh` | `abcd`  | Input longer than pattern truncates          |
| `C0`     | `ABC123`   | `A1`    | Non-matching characters are skiped           |
| `AAA`    | `123`      | (empty) | No matching characters found                 |
| `\E####` | `abc🙂`    | `abc`   | Transformation with no active state          |
