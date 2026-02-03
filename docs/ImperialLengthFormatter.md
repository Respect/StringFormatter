<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
-->

# ImperialLengthFormatter

The `ImperialLengthFormatter` promotes imperial length values between `in`, `ft`, `yd`, and `mi`.

- Non-numeric input is returned unchanged.
- Promotion is based on magnitude.
- Output uses symbols only (no spaces), e.g. `1ft`, `2yd`.

## Usage

```php
use Respect\StringFormatter\ImperialLengthFormatter;

$formatter = new ImperialLengthFormatter('in');

echo $formatter->format('12');
// Outputs: 1ft
```

## API

### `ImperialLengthFormatter::__construct`

- `__construct(string $unit)`

The `$unit` is the input unit (the unit you are providing values in).

Accepted units: `in`, `ft`, `yd`, `mi`.
