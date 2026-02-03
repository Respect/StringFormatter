<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
-->

# MassFormatter

The `MassFormatter` promotes metric *mass* values between `mg`, `g`, `kg`, and `t`.

- Non-numeric input is returned unchanged.
- Promotion is based on magnitude.
- Output uses symbols only (no spaces), e.g. `1kg`, `500mg`.

## Usage

```php
use Respect\StringFormatter\MassFormatter;

$formatter = new MassFormatter('g');

echo $formatter->format('1000');
// Outputs: 1kg

echo $formatter->format('0.001');
// Outputs: 1mg
```

## API

### `MassFormatter::__construct`

- `__construct(string $unit)`

The `$unit` is the input unit (the unit you are providing values in).

Accepted units: `mg`, `g`, `kg`, `t`.

### `format`

- `format(string $input): string`

If the input is numeric, it is promoted to the closest appropriate metric scale and returned with the corresponding symbol.
