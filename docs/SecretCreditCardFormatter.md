<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
-->

# SecretCreditCardFormatter

The `SecretCreditCardFormatter` formats and masks credit card numbers for secure display. It automatically detects card types, formats them appropriately, and masks sensitive portions.

## Usage

### Basic Usage

```php
use Respect\StringFormatter\SecretCreditCardFormatter;

$formatter = new SecretCreditCardFormatter();

echo $formatter->format('4123456789012345');
// Outputs: "4123 **** **** 2345" (Visa)

echo $formatter->format('341234567890123');
// Outputs: "3412 *******012 3" (Amex)

echo $formatter->format('5112345678901234');
// Outputs: "5112 **** **** 1234" (MasterCard)
```

### Input Cleaning

The formatter automatically removes non-digit characters from the input:

```php
use Respect\StringFormatter\SecretCreditCardFormatter;

$formatter = new SecretCreditCardFormatter();

echo $formatter->format('4123-4567-8901-2345');
// Outputs: "4123 **** **** 2345"
```

### Custom Masking

You can specify custom mask ranges, patterns, or mask characters:

```php
use Respect\StringFormatter\SecretCreditCardFormatter;

$formatter = new SecretCreditCardFormatter(maskRange: '6-12', maskChar: 'X');

echo $formatter->format('4123456789012345');
// Outputs: "4123 XXXXXX 2345"
```

## API

### `SecretCreditCardFormatter::__construct`

- `__construct(?string $pattern = null, ?string $maskRange = null, string $maskChar = '*')`

Creates a new secret credit card formatter instance.

**Parameters:**

- `$pattern`: Custom format pattern or null for auto-detection (default: null)
- `$maskRange`: Mask range specification or null for auto-detection (default: null)
- `$maskChar`: Character to use for masking (default: '\*')

### `format`

- `format(string $input): string`

Formats and masks the input credit card number.

**Parameters:**

- `$input`: The credit card number (can include spaces, dashes, dots, etc.)

**Returns:** The formatted and masked credit card number

## Masking

The formatter applies masking after formatting to ensure predictable positions:

| Card Type            | Example Input      | Mask Range  | Output                |
| -------------------- | ------------------ | ----------- | --------------------- |
| **Visa**             | `4123456789012345` | `6-9,11-14` | `4123 **** **** 2345` |
| **MasterCard**       | `5112345678901234` | `6-9,11-14` | `5112 **** **** 1234` |
| **American Express** | `341234567890123`  | `6-12`      | `3412 *******012 3`   |
| **Discover**         | `6011000990139424` | `6-9,11-14` | `6011 **** **** 9424` |
| **JCB**              | `3528000012345678` | `6-9,11-14` | `3528 **** **** 5678` |

## Examples

| Input                 | Output                | Card Type      |
| --------------------- | --------------------- | -------------- |
| `4123456789012345`    | `4123 **** **** 2345` | Visa           |
| `5112345678901234`    | `5112 **** **** 1234` | MasterCard     |
| `341234567890123`     | `3412 *******012 3`   | Amex           |
| `371234567890123`     | `3712 *******012 3`   | Amex           |
| `6011000990139424`    | `6011 **** **** 9424` | Discover       |
| `3528000012345678`    | `3528 **** **** 5678` | JCB            |
| `4123-4567-8901-2345` | `4123 **** **** 2345` | Visa (cleaned) |
| `4123 4567 8901 2345` | `4123 **** **** 2345` | Visa (cleaned) |

## Notes

- Composes `CreditCardFormatter` for formatting and `MaskFormatter` for masking
- Formats the card number first, then applies masking to the formatted string
- Mask ranges are applied to 1-based positions in the formatted string
- Commas in mask ranges specify multiple separate ranges to mask
- Non-digit characters are automatically removed from input
- Empty strings return formatted empty string with default pattern spacing
- Custom patterns follow `PatternFormatter` syntax (use `#` for digits)
- For custom masking, use `MaskFormatter` range syntax (1-based positions)
- Uses `CreditCardFormatter` for card type detection and formatting
