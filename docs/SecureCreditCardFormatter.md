<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
-->

# SecureCreditCardFormatter

The `SecureCreditCardFormatter` formats and masks credit card numbers for secure display. It automatically detects card types, formats them appropriately, and masks sensitive portions.

## Usage

### Basic Usage

```php
use Respect\StringFormatter\SecureCreditCardFormatter;

$formatter = new SecureCreditCardFormatter();

echo $formatter->format('4123456789012345');
// Outputs: "4123 **** **** 2345" (Visa)

echo $formatter->format('341234567890123');
// Outputs: "3412 ****** 90123" (Amex, 4-6-5 format)

echo $formatter->format('5112345678901234');
// Outputs: "5112 **** **** 1234" (MasterCard)

echo $formatter->format('36123456789012');
// Outputs: "3612 ****** 9012" (Diners Club, 4-6-4 format)

echo $formatter->format('4123456789012345678');
// Outputs: "4123 **** **** **** 678" (Visa 19-digit)
```

### Custom Mask Character

```php
use Respect\StringFormatter\SecureCreditCardFormatter;

$formatter = new SecureCreditCardFormatter('X');

echo $formatter->format('4123456789012345');
// Outputs: "4123 XXXX XXXX 2345"
```

### Input Cleaning

The formatter automatically removes non-digit characters from the input:

```php
use Respect\StringFormatter\SecureCreditCardFormatter;

$formatter = new SecureCreditCardFormatter();

echo $formatter->format('4123-4567-8901-2345');
// Outputs: "4123 **** **** 2345"
```

## API

### `SecureCreditCardFormatter::__construct`

- `__construct(string $maskChar = '*')`

Creates a new secure credit card formatter instance.

**Parameters:**

- `$maskChar`: Character to use for masking (default: '\*')

### `format`

- `format(string $input): string`

Formats and masks the input credit card number.

**Parameters:**

- `$input`: The credit card number (can include spaces, dashes, dots, etc.)

**Returns:** The formatted and masked credit card number

## Masking

The formatter applies masking after formatting to ensure predictable positions:

| Card Type            | Example Input         | Mask Range        | Output                    |
| -------------------- | --------------------- | ----------------- | ------------------------- |
| **Visa** (16)        | `4123456789012345`    | `6-9,11-14`       | `4123 **** **** 2345`     |
| **Visa** (19)        | `4123456789012345678` | `6-9,11-14,16-19` | `4123 **** **** **** 678` |
| **MasterCard**       | `5112345678901234`    | `6-9,11-14`       | `5112 **** **** 1234`     |
| **American Express** | `341234567890123`     | `6-11`            | `3412 ****** 90123`       |
| **Discover**         | `6011000990139424`    | `6-9,11-14`       | `6011 **** **** 9424`     |
| **JCB**              | `3528000012345678`    | `6-9,11-14`       | `3528 **** **** 5678`     |
| **Diners Club** (14) | `36123456789012`      | `6-11`            | `3612 ****** 9012`        |
| **UnionPay**         | `6212345678901234`    | `6-9,11-14`       | `6212 **** **** 1234`     |
| **RuPay**            | `8112345678901234`    | `6-9,11-14`       | `8112 **** **** 1234`     |

## Examples

| Input                 | Output                    | Card Type    |
| --------------------- | ------------------------- | ------------ |
| `4123456789012345`    | `4123 **** **** 2345`     | Visa         |
| `4123456789012345678` | `4123 **** **** **** 678` | Visa (19)    |
| `5112345678901234`    | `5112 **** **** 1234`     | MasterCard   |
| `341234567890123`     | `3412 ****** 90123`       | Amex         |
| `371234567890123`     | `3712 ****** 90123`       | Amex         |
| `6011000990139424`    | `6011 **** **** 9424`     | Discover     |
| `3528000012345678`    | `3528 **** **** 5678`     | JCB          |
| `36123456789012`      | `3612 ****** 9012`        | Diners Club  |
| `6212345678901234`    | `6212 **** **** 1234`     | UnionPay     |
| `8112345678901234`    | `8112 **** **** 1234`     | RuPay        |
| `4123-4567-8901-2345` | `4123 **** **** 2345`     | Visa (clean) |

## Notes

- Composes `CreditCardFormatter` for formatting and `MaskFormatter` for masking
- Formats the card number first, then applies masking to the formatted string
- Mask ranges are applied to 1-based positions in the formatted string
- Non-digit characters are automatically removed from input
- Inputs with fewer than 9 digits are returned as cleaned digits without formatting or masking
- Uses `CreditCardFormatter` for card type detection and formatting
