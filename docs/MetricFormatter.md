<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
-->

# MetricFormatter

The `MetricFormatter` promotes metric *length* values between `mm`, `cm`, `m`, and `km`.

- Non-numeric input is returned unchanged.
- Promotion is based on magnitude.
- Output uses symbols only (no spaces), e.g. `1km`, `10cm`.

## Usage

```php
use Respect\StringFormatter\MetricFormatter;

$formatter = new MetricFormatter('m');

echo $formatter->format('1000');
// Outputs: 1km

echo $formatter->format('0.1');
// Outputs: 10cm
```

## API

### `MetricFormatter::__construct`

- `__construct(string $unit)`

The `$unit` is the input unit (the unit you are providing values in).

Accepted units: `mm`, `cm`, `m`, `km`.

### `format`

- `format(string $input): string`

If the input is numeric, it is promoted to the closest appropriate metric scale and returned with the corresponding symbol.

## Behavior

### Promotion rule

The formatter chooses a unit where the promoted value is in the range $[1, 1000)$ when possible. If not possible, it uses the smallest (`mm`) or largest (`km`) unit as needed.

### No rounding

Values are not rounded. Trailing fractional zeros are trimmed:

```php
$formatter = new MetricFormatter('m');

echo $formatter->format('1.23000');
// Outputs: 1.23m
```