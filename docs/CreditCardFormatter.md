<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
-->

# CreditCardFormatter

The `CreditCardFormatter` formats credit card numbers with automatic card type detection. It supports major card networks including Visa, MasterCard, American Express, Discover, JCB, Diners Club, UnionPay, and RuPay.

## Usage

### Basic Usage with Auto-Detection

```php
use Respect\StringFormatter\CreditCardFormatter;

$formatter = new CreditCardFormatter();

echo $formatter->format('4123456789012345');
// Outputs: "4123 4567 8901 2345" (Visa detected)

echo $formatter->format('371234567890123');
// Outputs: "3712 345678 90123" (Amex, 4-6-5 format)

echo $formatter->format('5112345678901234');
// Outputs: "5112 3456 7890 1234" (MasterCard detected)

echo $formatter->format('36123456789012');
// Outputs: "3612 345678 9012" (Diners Club, 4-6-4 format)

echo $formatter->format('4123456789012345678');
// Outputs: "4123 4567 8901 2345 678" (Visa 19-digit)
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

## API

### `format`

- `format(string $input): string`

Formats the input credit card number.

**Parameters:**

- `$input`: The credit card number (can include spaces, dashes, dots, etc.)

**Returns:** The formatted credit card number

## Auto-Detection

The formatter automatically detects card type based on prefix and length:

| Card Type            | Prefix Ranges        | Length     | Format                |
| -------------------- | -------------------- | ---------- | --------------------- |
| **American Express** | 34, 37               | 15         | `#### ###### #####`   |
| **Diners Club**      | 300-305, 309, 36, 38 | 14         | `#### ###### ####`    |
| **Diners Club**      | 36                   | 16         | `#### #### #### ####` |
| **Visa**             | 4                    | 13, 16     | `#### #### #### ####` |
| **Visa**             | 4                    | 19         | `#### #### #### #### ###` |
| **MasterCard**       | 51-55, 2221-2720     | 16         | `#### #### #### ####` |
| **Discover**         | 6011, 644-649, 65    | 16         | `#### #### #### ####` |
| **Discover**         | 6011, 644-649, 65    | 19         | `#### #### #### #### ###` |
| **JCB**              | 3528-3589            | 16         | `#### #### #### ####` |
| **JCB**              | 3528-3589            | 19         | `#### #### #### #### ###` |
| **UnionPay**         | 62                   | 16         | `#### #### #### ####` |
| **UnionPay**         | 62                   | 19         | `#### #### #### #### ###` |
| **RuPay**            | 60, 65, 81, 82, 508  | 16         | `#### #### #### ####` |

Cards with more than 16 digits automatically use the 19-digit pattern: `#### #### #### #### ###`

## Examples

| Input                 | Output                    | Card Type    |
| --------------------- | ------------------------- | ------------ |
| `4123456789012345`    | `4123 4567 8901 2345`     | Visa         |
| `4123456789012345678` | `4123 4567 8901 2345 678` | Visa (19)    |
| `5112345678901234`    | `5112 3456 7890 1234`     | MasterCard   |
| `341234567890123`     | `3412 345678 90123`       | Amex         |
| `371234567890123`     | `3712 345678 90123`       | Amex         |
| `6011000990139424`    | `6011 0009 9013 9424`     | Discover     |
| `3528000012345678`    | `3528 0000 1234 5678`     | JCB          |
| `36123456789012`      | `3612 345678 9012`        | Diners Club  |
| `6212345678901234`    | `6212 3456 7890 1234`     | UnionPay     |
| `8112345678901234`    | `8112 3456 7890 1234`     | RuPay        |
| `1234567890123456`    | `1234 5678 9012 3456`     | Unknown      |
| `4123-4567-8901-2345` | `4123 4567 8901 2345`     | Visa (clean) |

## Notes

- Non-digit characters are automatically removed from the input
- Card type detection is based on card prefix and length (not Luhn validation)
- If card type cannot be determined, uses the default 4-4-4-4 pattern
- Uses `PatternFormatter` internally for formatting
- For custom formatting patterns, use `PatternFormatter` directly
