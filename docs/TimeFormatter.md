<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
-->

# TimeFormatter

The `TimeFormatter` promotes time values between multiple units.

- Non-numeric input is returned unchanged.
- Promotion is based on magnitude.
- Output uses symbols only (no spaces), e.g. `1h`, `500ms`.

## Usage

```php
use Respect\StringFormatter\TimeFormatter;

$formatter = new TimeFormatter('s');

echo $formatter->format('60');
// Outputs: 1min

echo $formatter->format('0.001');
// Outputs: 1ms
```

## API

### `TimeFormatter::__construct`

- `__construct(string $unit)`

The `$unit` is the input unit (the unit you are providing values in).

Accepted symbols:

- `ns`, `us`, `ms`, `s`, `min`, `h`, `d`, `w`, `mo`, `y`, `dec`, `c`, `mil`

## Notes on non-standard symbols

- `y` uses a fixed year of 365 days.
- `mo` uses 1/12 of a fixed year (approx. 30.41 days).
- `w` uses 7 days.
- `dec`, `c`, and `mil` are based on that fixed year.
