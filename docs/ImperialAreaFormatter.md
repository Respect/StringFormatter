<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
-->

# ImperialAreaFormatter

The `ImperialAreaFormatter` promotes imperial area values between `in²`, `ft²`, `yd²`, `ac`, and `mi²`.

- Non-numeric input is returned unchanged.
- Promotion is based on magnitude.
- Output uses symbols only (no spaces), e.g. `1ft²`, `2ac`.

## Usage

```php
use Respect\StringFormatter\ImperialAreaFormatter;

$formatter = new ImperialAreaFormatter('ft^2');

echo $formatter->format('43560');
// Outputs: 1ac
```

## API

### `ImperialAreaFormatter::__construct`

- `__construct(string $unit)`

The `$unit` is the input unit (the unit you are providing values in).

Accepted units: `in^2`, `ft^2`, `yd^2`, `ac`, `mi^2`.
