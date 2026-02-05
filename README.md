<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
-->

# Respect\StringFormatter

[![Build Status](https://img.shields.io/github/actions/workflow/status/Respect/StringFormatter/continuous-integration.yml?branch=main&style=flat-square)](https://github.com/Respect/StringFormatter/actions/workflows/continuous-integration.yml)
[![Code Coverage](https://img.shields.io/codecov/c/github/Respect/StringFormatter?style=flat-square)](https://codecov.io/gh/Respect/StringFormatter)
[![Latest Stable Version](https://img.shields.io/packagist/v/respect/string-formatter.svg?style=flat-square)](https://packagist.org/packages/respect/string-formatter)
[![Total Downloads](https://img.shields.io/packagist/dt/respect/string-formatter.svg?style=flat-square)](https://packagist.org/packages/respect/string-formatter)
[![License](https://img.shields.io/packagist/l/respect/string-formatter.svg?style=flat-square)](https://packagist.org/packages/respect/string-formatter)

A powerful and flexible PHP library for formatting and transforming strings.

## Installation

```bash
composer require respect/string-formatter
```

## Usage

You can use individual formatters directly or chain multiple formatters together using the `FormatterBuilder`:

```php
use Respect\StringFormatter\FormatterBuilder as f;

echo f::create()
    ->mask('7-12')
    ->pattern('#### #### #### ####')
    ->format('1234123412341234');
// Output: 1234 12** **** 1234
```

### Using Formatters as Modifiers

The `PlaceholderFormatter` allows you to use any formatter as a modifier within templates:

```php
use Respect\StringFormatter\PlaceholderFormatter;

$formatter = new PlaceholderFormatter([
    'date' => '2024-01-15',
    'amount' => '1234.56',
    'phone' => '1234567890',
]);

echo $formatter->format('Date: {{date|date:Y/m/d}}, Amount: ${{amount|number:2}}, Phone: {{phone|pattern:(###) ###-####}}');
// Output: Date: 2024/01/15, Amount: $1,234.56, Phone: (123) 456-7890
```

See the [PlaceholderFormatter documentation](docs/PlaceholderFormatter.md) and [FormatterModifier documentation](docs/modifiers/FormatterModifier.md) for more details.

## Formatters

| Formatter                                                  | Description                                                      |
| ---------------------------------------------------------- | ---------------------------------------------------------------- |
| [AreaFormatter](docs/AreaFormatter.md)                     | Metric area promotion (mm², cm², m², a, ha, km²)                 |
| [DateFormatter](docs/DateFormatter.md)                     | Date and time formatting with flexible parsing                   |
| [ImperialAreaFormatter](docs/ImperialAreaFormatter.md)     | Imperial area promotion (in², ft², yd², ac, mi²)                 |
| [ImperialLengthFormatter](docs/ImperialLengthFormatter.md) | Imperial length promotion (in, ft, yd, mi)                       |
| [ImperialMassFormatter](docs/ImperialMassFormatter.md)     | Imperial mass promotion (oz, lb, st, ton)                        |
| [MaskFormatter](docs/MaskFormatter.md)                     | Range-based string masking with Unicode support                  |
| [MassFormatter](docs/MassFormatter.md)                     | Metric mass promotion (mg, g, kg, t)                             |
| [MetricFormatter](docs/MetricFormatter.md)                 | Metric length promotion (mm, cm, m, km)                          |
| [NumberFormatter](docs/NumberFormatter.md)                 | Number formatting with thousands and decimal separators          |
| [PatternFormatter](docs/PatternFormatter.md)               | Pattern-based string filtering with placeholders                 |
| [PlaceholderFormatter](docs/PlaceholderFormatter.md)       | Template interpolation with placeholder replacement              |
| [TimeFormatter](docs/TimeFormatter.md)                     | Time promotion (mil, c, dec, y, mo, w, d, h, min, s, ms, us, ns) |

## Contributing

Please see our [Contributing Guide](CONTRIBUTING.md) for information on how to contribute to this project.

## License

This project is licensed under the ISC License - see the LICENSE file for details.
