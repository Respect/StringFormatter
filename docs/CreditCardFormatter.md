<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
-->

# CreditCardFormatter

The `CreditCardFormatter` formats credit card numbers with automatic card type detection. It supports major card types including Visa, MasterCard, American Express, Discover, and JCB.

## Usage

### Basic Usage with Auto-Detection

```php
use Respect\StringFormatter\CreditCardFormatter;

$formatter = new CreditCardFormatter();

echo $formatter->format('4123456789012345');
// Outputs: "4123 4567 8901 2345" (Visa detected)

echo $formatter->format('371234567890123');
// Outputs: "3712 345678 90123" (Amex, different pattern)

echo $formatter->format('5112345678901234');
// Outputs: "5112 3456 7890 1234" (MasterCard detected)
```

### Input Cleaning

The formatter automatically removes non-digit characters from the input:

```php
use Respect\StringFormatter\CreditCardFormatter;

$formatter = new CreditCardFormatter();

echo $formatter->format('4123-4567-8901-2345');
// Outputs: "4123 4567 8901 2345"

echo $formatter->format('4123 4567 8901 2345');
// Outputs: "4123 4567 8901 2345"

echo $formatter->format('4123.4567.8901.2345');
// Outputs: "4123 4567 8901 2345"
```

### Custom Pattern

You can specify a custom pattern to override auto-detection:

```php
use Respect\StringFormatter\CreditCardFormatter;

$formatter = new CreditCardFormatter('####-####-####-####');

echo $formatter->format('4123456789012345');
// Outputs: "4123-4567-8901-2345"

$formatterCompact = new CreditCardFormatter('################');

echo $formatterCompact->format('4123456789012345');
// Outputs: "4123456789012345"
```

## API

### `CreditCardFormatter::__construct`

- `__construct(?string $pattern = null)`

Creates a new credit card formatter instance.

**Parameters:**

- `$pattern`: Custom format pattern or null for auto-detection (default: null)

**If null**: The formatter automatically detects the card type and applies the appropriate pattern

**If provided**: Uses the specified pattern for all cards

### `format`

- `format(string $input): string`

Formats the input credit card number.

**Parameters:**

- `$input`: The credit card number (can include spaces, dashes, dots, etc.)

**Returns:** The formatted credit card number

## Auto-Detection

The formatter automatically detects card type based on prefix and length:

| Card Type            | Prefix Ranges     | Length     | Format Pattern                          |
| -------------------- | ----------------- | ---------- | --------------------------------------- |
| **Visa**             | 4                 | 13, 16, 19 | `#### #### #### ####`                   |
| **MasterCard**       | 51-55             | 16         | `#### #### #### ####`                   |
| **American Express** | 34, 37            | 15         | `#### ########## ######` - 4-6-5 format |
| **Discover**         | 6011, 644-649, 65 | 16         | `#### #### #### ####`                   |
| **JCB**              | 3528-3589         | 16         | `#### #### #### ####`                   |
| **Unknown**          | (any)             | any        | `#### #### #### ####` - default pattern |

## Examples

| Input                 | Output                | Card Type      |
| --------------------- | --------------------- | -------------- |
| `4123456789012345`    | `4123 4567 8901 2345` | Visa           |
| `5112345678901234`    | `5112 3456 7890 1234` | MasterCard     |
| `341234567890123`     | `3412 345678 90123`   | Amex           |
| `371234567890123`     | `3712 345678 90123`   | Amex           |
| `6011000990139424`    | `6011 0009 9013 9424` | Discover       |
| `3528000012345678`    | `3528 0000 1234 5678` | JCB            |
| `1234567890123456`    | `1234 5678 9012 3456` | Unknown        |
| `4123-4567-8901-2345` | `4123 4567 8901 2345` | Visa (cleaned) |
| `4123 4567 8901 2345` | `4123 4567 8901 2345` | Visa (cleaned) |

## Notes

- Non-digit characters are automatically removed from the input
- Card type detection is based on card prefix and length (not Luhn validation)
- If card type cannot be determined, uses the default pattern `#### #### #### ####`
- Uses `PatternFormatter` internally for formatting
- Empty strings return empty strings
- Numbers longer than the pattern aretruncated to fit the pattern
- Custom patterns follow `PatternFormatter` syntax (use `#` for digits)
