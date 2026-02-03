<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
-->

# ImperialMassFormatter

The `ImperialMassFormatter` promotes imperial mass values between `oz`, `lb`, `st`, and `ton`.

- Non-numeric input is returned unchanged.
- Promotion is based on magnitude.
- Output uses symbols only (no spaces), e.g. `1lb`, `8oz`.

## Usage

```php
use Respect\StringFormatter\ImperialMassFormatter;

$formatter = new ImperialMassFormatter('oz');

echo $formatter->format('16');
// Outputs: 1lb
```

## API

### `ImperialMassFormatter::__construct`

- `__construct(string $unit)`

The `$unit` is the input unit (the unit you are providing values in).

Accepted units: `oz`, `lb`, `st`, `ton`.

## Notes

- `ton` represents the imperial long ton (`2240lb`).
