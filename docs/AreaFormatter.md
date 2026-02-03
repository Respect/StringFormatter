<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
-->

# AreaFormatter

The `AreaFormatter` promotes metric area values between `mm²`, `cm²`, `m²`, `a`, `ha`, and `km²`.

- Non-numeric input is returned unchanged.
- Promotion is based on magnitude.
- Output uses symbols only (no spaces), e.g. `1m²`, `2ha`.

## Usage

```php
use Respect\StringFormatter\AreaFormatter;

$formatter = new AreaFormatter('m^2');

echo $formatter->format('10000');
// Outputs: 1ha
```

## API

### `AreaFormatter::__construct`

- `__construct(string $unit)`

The `$unit` is the input unit (the unit you are providing values in).

Accepted units: `mm^2`, `cm^2`, `m^2`, `a`, `ha`, `km^2`.
